<?php
	include("../functions.php");

	if((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])) ) 
		header("Location: login.php");

	if($_SESSION['user_level'] != "staff")
		header("Location: login.php");

	if (isset($_POST['btnMenuID'])) {

		$menuID = $sqlconnection->real_escape_string($_POST['btnMenuID']);

		$menuItemQuery = "SELECT itemID,menuItemName FROM tbl_menuitem WHERE menuID = " . $menuID;

		if ($menuItemResult = $sqlconnection->query($menuItemQuery)) {
			if ($menuItemResult->num_rows > 0) {
				$counter = 0;
				while($menuItemRow = $menuItemResult->fetch_array(MYSQLI_ASSOC)) {

					if ($counter >=3) {
						echo "</tr>";
						$counter = 0;
					}

					if($counter == 0) {
						echo "<tr>";
					}

					echo "<td><button style='margin-bottom:4px;white-space: normal;' class='btn btn-warning' onclick = 'setQty({$menuItemRow['itemID']})'>{$menuItemRow['menuItemName']}</button></td>";

					$counter++;
				}
			}

			else {
				echo "<tr><td>No item in this menu</td></tr>";
			}
			
		}
	}

if (isset($_POST['btnMenuItemID']) && isset($_POST['qty'])) {
    $menuItemID = $sqlconnection->real_escape_string($_POST['btnMenuItemID']);
    $quantity = $sqlconnection->real_escape_string($_POST['qty']);
    $takeaway = isset($_POST['takeaway']) && $_POST['takeaway'] === 'true';

    $menuItemQuery = "SELECT mi.itemID, mi.menuItemName, mi.price, m.menuName 
                      FROM tbl_menuitem mi 
                      LEFT JOIN tbl_menu m ON mi.menuID = m.menuID 
                      WHERE itemID = " . $menuItemID;

    if ($menuItemResult = $sqlconnection->query($menuItemQuery)) {
        if ($menuItemResult->num_rows > 0) {
            $menuItemRow = $menuItemResult->fetch_array(MYSQLI_ASSOC);

            $price = $menuItemRow['price'];
            $total = $price * $quantity;

            // Recargo por llevar
            if ($takeaway) {
                $total += 2.00;
            }

            echo "
            <tr>
                <input type='hidden' name='itemID[]' value='{$menuItemRow['itemID']}' />
                <input type='hidden' name='itemTakeaway[]' value='" . ($takeaway ? "1" : "0") . "' />
                <td>
                    {$menuItemRow['menuName']} : {$menuItemRow['menuItemName']}
                    " . ($takeaway ? "<span class='badge bg-info ms-2'>Para llevar +S/.2.00</span>" : "") . "
                </td>
                <td>S/. " . number_format($price, 2, '.', '') . "</td>
                <td><input type='number' min='1' max='50' name='itemqty[]' value='{$quantity}' class='form-control w-75 mx-auto' readonly /></td>
                <td class='item-total'>" . number_format($total, 2, '.', '') . "</td>
                <td><button class='btn btn-danger' type='button' onclick='deleteRow(this)'><i class='fas fa-times'></i></button></td>
            </tr>
            ";
        }
    }
}



	
?>