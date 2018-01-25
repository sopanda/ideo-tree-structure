<?php
if(isset($_GET['operation'])) {
	try {
		$pdo = new PDO('mysql:host=55.mysql.ideo;dbname=wp-szkolenie-3', 'root', 'root');
		$result = null;
		switch($_GET['operation']) {
			case 'get_node':
				$sth = $pdo->query('SELECT id AS id, IF (parent_id = 0, "#", parent_id) AS parent, name as text FROM drzewko ORDER BY parent_id, position');
				$categories = array();
				while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
					array_push($categories, array(
						'id' => $row['id'],
						'parent' => $row['parent'],
						'text' => $row['text'],
					));
				}
				$result = $categories;
				break;
			case 'create_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$nodeText = isset($_GET['text']) && $_GET['text'] !== '' ? $_GET['text'] : '';
				$statement = $pdo->prepare("INSERT INTO drzewko(name, text, parent_id) VALUES(?, ?, ?)");
				$statement->execute(array($nodeText, $nodeText, $node));
				$result = array('id' => $pdo->lastInsertId());
				break;
			case 'rename_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$nodeText = isset($_GET['text']) && $_GET['text'] !== '' ? $_GET['text'] : '';
				$statement = $pdo->prepare("UPDATE drzewko SET name = ?, text = ? WHERE id = ?");
				$statement->execute(array($nodeText, $nodeText, $node));
				break;
			case 'delete_withChild':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$delete = $pdo->prepare("DELETE FROM drzewko WHERE id = :id");
				$delete ->bindValue(':id', $node);
				$delete-> execute();
				$deleteChildren = $pdo->prepare("DELETE FROM drzewko WHERE parent_id = :pId");
				$deleteChildren ->bindValue(':pId', $node);
				$deleteChildren-> execute();
				break;
			case 'delete_onlyMe':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$nodeParent = isset($_GET['node_parent']) && $_GET['node_parent'] !== '' ? $_GET['node_parent'] : '';
				$sqlNewParent = $pdo->prepare("UPDATE drzewko SET parent_id = ? WHERE parent_id = ?");
				$sqlNewParent->execute(array($nodeParent, $node));
				$statement = $pdo->prepare("DELETE FROM drzewko WHERE id = :id");
				$statement ->bindValue(':id', $node);
				$statement->execute();
				break;
			// case 'move_node':
			// 	$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
			// 	$new_position = isset($_GET['new_position']) && $_GET['new_position'] !== '' ? $_GET['new_position'] : '';
			// 	$old_position = isset($_GET['old_position']) && $_GET['old_position'] !== '' ? $_GET['old_position'] : '';
			// 	$new_par = isset($_GET['new_parent']) && $_GET['new_parent'] !== '' ? $_GET['new_parent'] : '';
			// 	$old_par = isset($_GET['old_parent']) && $_GET['old_parent'] !== '' ? $_GET['old_parent'] : '';
			// 	$old_position += 1;
			// 	$new_position += 1;
			// 	function excludePosition($conn, $old_par, $old_position) {
			// 		$sql = "UPDATE `drzewko` SET `position` = `position` - 1 WHERE `parent_id`='".$old_par."' AND `position` > '".$old_position."'";
			// 		// mysqli_query($conn, $sql);
			// 	}
			// 	function includePosition($conn, $node, $new_par, $new_position) {
			// 		$sql1 = "UPDATE `drzewko` SET `position` = `position` + 1 WHERE `parent_id`='".$new_par."' AND `position` >= '".$new_position."'";
			// 		// mysqli_query($conn, $sql1);
			// 		$sql2 = "UPDATE `drzewko` SET `parent_id` = '".$new_par."', `position` = '".$new_position."' WHERE `id`='".$node."'";
			// 		// mysqli_query($conn, $sql2);
			// 	}
			// 	excludePosition($conn, $old_par, $old_position);
    		// 	includePosition($conn, $node, $new_par, $new_position);
			// 	break;
			default:
				throw new Exception('Unsupported operation: ' . $_GET['operation']);
				break;
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
	}
	catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
}
?>