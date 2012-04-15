<?php
/*
 * Shop.class.php
 * 
 * Copyright (c) 2012 Andrew Jordan
 * 
 * Permission is hereby granted, free of charge, to any person obtaining 
 * a copy of this software and associated documentation files (the 
 * "Software"), to deal in the Software without restriction, including 
 * without limitation the rights to use, copy, modify, merge, publish, 
 * distribute, sublicense, and/or sell copies of the Software, and to 
 * permit persons to whom the Software is furnished to do so, subject to 
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be 
 * included in all copies or substantial portions of the Software. 
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY 
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, 
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
 
class Shop{
	
	private $pdo_conn;
	
	private $user_id;
	
	function __construct(&$db, $aUserID=null){
		$this->pdo_conn = &$db;
		if(!is_null($aUserID))
			$this->user_id = $aUserID;
	}
	
	public function getItems(){
		$sql = "SELECT ShopItems.item_id, ShopItems.name, ShopItems.price, ShopItems.description
				FROM ShopItems WHERE active=1";
		$statement = $this->pdo_conn->query($sql);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		return $statement->fetchAll();
	}
	
	public function getItem($item_id){
		$sql = "SELECT ShopItems.item_id, ShopItems.name, ShopItems.price, ShopItems.description
				FROM ShopItems WHERE active=1 AND item_id=?";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($item_id));
		if($statement->rowCount() == 1)
			return $statement->fetch();
		else return false;
	}
	
	public function purchaseItem($item_id){
		$item = $this->getItem($item_id);
		$sql = "INSERT INTO ShopTransactions (user_id, item_id, value, date)
					VALUES ($this->user_id, ?, ".$item['price'].", ".time().")";
		$statement = $this->pdo_conn->prepare($sql);
		$statement->execute(array($item_id));
		if($statement->rowCount() == 1){
			$transaction_id = $this->pdo_conn->lastInsertId('transaction_id');
			$sql2 = "INSERT INTO Inventory (user_id, transaction_id)
						VALUES($this->user_id, $transaction_id)";
			$statement2 = $this->pdo_conn->query($sql2);
			return TRUE;
		}
		else
			return FALSE;
	}
}
?>
