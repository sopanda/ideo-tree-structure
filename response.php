<?php
$servername = "55.mysql.ideo";
$username = "root";
$password = "root";
$dbname = "wp-szkolenie-3";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
if(isset($_GET['operation'])) {
	try {
		$result = null;
		switch($_GET['operation']) {
			case 'get_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$sql = "SELECT * FROM `drzewko` ORDER BY `parent_id`, `position`";
				$res = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
				if($res->num_rows <= 0) {
				 //add condition when result is zero
				} else {
					//iterate on results row and create new index array of data
					while( $row = mysqli_fetch_assoc($res) ) { 
						$data[] = $row;
					}
					$itemsByReference = array();
				// Build array of item references:
				foreach($data as $key => &$item) {
				   $itemsByReference[$item['id']] = &$item;
				   // Children array:
				   $itemsByReference[$item['id']]['children'] = array();
				   // Empty data class (so that json_encode adds "data: {}" ) 
				   $itemsByReference[$item['id']]['data'] = new StdClass();
				}
				// Set items as children of the relevant parent item.
				foreach($data as $key => &$item)
				   if($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
					  $itemsByReference [$item['parent_id']]['children'][] = &$item;

				// Remove items that were added to parents elsewhere:
				foreach($data as $key => &$item) {
				   if($item['parent_id'] && isset($itemsByReference[$item['parent_id']]))
					  unset($data[$key]);
				}
				}
				/* FIXED BUG WITH DATA */
				$result = array();
				foreach($data as $rec)
				{
					$result[] = $rec;
				}
				break;
			case 'create_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$nodeText = isset($_GET['text']) && $_GET['text'] !== '' ? $_GET['text'] : '';
				$sql ="INSERT INTO `drzewko` (`name`, `text`, `parent_id`) VALUES('".$nodeText."', '".$nodeText."', '".$node."')";
				mysqli_query($conn, $sql);
				$result = array('id' => mysqli_insert_id($conn));
				break;
			case 'rename_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$nodeText = isset($_GET['text']) && $_GET['text'] !== '' ? $_GET['text'] : '';
				$sql ="UPDATE `drzewko` SET `name`='".$nodeText."',`text`='".$nodeText."' WHERE `id`= '".$node."'";
				mysqli_query($conn, $sql);
				break;
			case 'delete_withChild':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$sql ="DELETE FROM `drzewko` WHERE `id`= '".$node."'";
				mysqli_query($conn, $sql);
				break;
			case 'delete_onlyMe':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$nodeParent = isset($_GET['node_parent']) && $_GET['node_parent'] !== '' ? $_GET['node_parent'] : '';
				$sqlNewParent = "UPDATE `drzewko` SET `parent_id`='".$nodeParent."' WHERE `parent_id`='".$node."'";
				$sql ="DELETE FROM `drzewko` WHERE `id`= '".$node."'";
				mysqli_query($conn, $sqlNewParent);
				mysqli_query($conn, $sql);
				break;
			case 'move_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$new_position = isset($_GET['new_position']) && $_GET['new_position'] !== '' ? $_GET['new_position'] : '';
				$old_position = isset($_GET['old_position']) && $_GET['old_position'] !== '' ? $_GET['old_position'] : '';
				$new_par = isset($_GET['new_parent']) && $_GET['new_parent'] !== '' ? $_GET['new_parent'] : '';
				$old_par = isset($_GET['old_parent']) && $_GET['old_parent'] !== '' ? $_GET['old_parent'] : '';
				$old_position += 1;
				$new_position += 1;
				function excludePosition($conn, $old_par, $old_position) {
					$sql = "UPDATE `drzewko` SET `position` = `position` - 1 WHERE `parent_id`='".$old_par."' AND `position` > '".$old_position."'";
					mysqli_query($conn, $sql);
				}
				function includePosition($conn, $node, $new_par, $new_position) {
					$sql1 = "UPDATE `drzewko` SET `position` = `position` + 1 WHERE `parent_id`='".$new_par."' AND `position` >= '".$new_position."'";
					mysqli_query($conn, $sql1);
					$sql2 = "UPDATE `drzewko` SET `parent_id` = '".$new_par."', `position` = '".$new_position."' WHERE `id`='".$node."'";
					mysqli_query($conn, $sql2);
				}
				excludePosition($conn, $old_par, $old_position);
    			includePosition($conn, $node, $new_par, $new_position);
				break;
			default:
				throw new Exception('Unsupported operation: ' . $_GET['operation']);
				break;
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
	}
	catch (Exception $e) {
		header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
		header('Status:  500 Server Error');
		echo $e->getMessage();
	}
	die();
}
?>