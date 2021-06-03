<?php 
 include '../begin.php';
?>
<div class="container-fluid padzero process-area">
			<div class="container">
				<div class="col-md-12 heading text-center"><span>Invoice</span></div>
				<div class="col-md-12 whitearea">
					<div class="col-md-12 forms">
						<form method="post">
							<div class="col-md-12 padzero">
								<div class="col-md-6 cleft padzero">
								 <label>Select Party</label>
								 <select  class="user-textline" required name="company" id="company">
								       <option value=""></option>
								 <?php 
									$get_party_company=mysqli_query($conn, "SELECT company_name FROM party_details WHERE is_deleted=0");
									while($comapany_name_list=mysqli_fetch_assoc($get_party_company)){
										echo("<option>".$comapany_name_list["company_name"]."</option>");
									}
									
								 ?>
								 </select>
								</div>
								<div class="col-md-6 cright padzero">
									<label>Conformation No</label>
									<input type="number" class="user-textline" name="conformation_no">
								</div>
							</div>
							<div class="col-md-12 padzero">
								<div class="col-md-6 cleft padzero">
								 <label>E-way Bill.No</label>
								 <input type="text" class="user-textline" name="e_way_bill_no">
								</div>
								<div class="col-md-6 cright padzero">
									<label style="padding-right:1px">Vehicle No</label><span>(e.g:TN 88AA 1234)</span>
									<input type="text" class="user-textline" style="text-transform: uppercase" pattern="[A-Za-z]){2,3}(|\d)(?:[0-9]){1,2}(|)(?:[A-Za-z]){1,2}(|\d)([0-9]){1,4})|(([A-Za-z]){2,3}(|-)([0-9]){1,4}" required name="vehicle_no">
								</div>
								<!--(([A-Za-z]){2,3}(|-)(?:[0-9]){1,2}(|-)(?:[A-Za-z]){2}(|-)([0-9]){1,4})|(([A-Za-z]){2,3}(|-)([0-9]){1,4})-->
							</div>
						   <div class="col-md-12 padzero">
								<div class="col-md-6 cleft padzero">
								 <label>Transport Name</label>
								 <input type="text" class="user-textline" name="transport_name">
								</div>
								<div class="col-md-6 cleft padzero">
								 <label>Truck Freight</label>
								 <input type="number" class="user-textline" name="truck_freight" onblur="verifyTruckfrieght()" id="truck_freight">
								</div>
						   </div>
						   <div class="col-md-12 padzero">
						   		<div class="col-md-6 cleft padzero">
								 <label>CGST</label>
								 <input type="text" class="user-textline" name="CGST">
								</div>
								<div class="col-md-6 cright padzero">
								 <label>SGST</label>
								 <input type="text" class="user-textline" name="SGST">
								</div>
						   </div>
						   <div class="col-md-12 padzero">
						   		<div class="col-md-6 cleft padzero">
								 <label>IGST</label>
								 <input type="text" class="user-textline" name="IGST">
								</div>
								<div class="col-md-6 cright padzero">
								 <label>Date</label>
								 <input type="date" class="user-textline" name="date_load" required>
								</div>
						   </div>
						   <div class="col-md-12 table-sec">
							
								<table border="0" cellspacing="4" width="70%" id="load_details">
									<tr>
										<th>Description of Goods</th>
										<th>No.of Bags</th>
										<th>Quantity</th>
										<th>Rate Per Unit Rs.</th>
									</tr>
									<tr>
										<td><select name="description_goods[]" id="description_good" class="user_box" required>
												<option value=""></option>
											<?php 
											    $get_goods_name=mysqli_query($conn, "SELECT goods_name FROM goods_details WHERE is_deleted=0");
												while($goods_list=mysqli_fetch_assoc($get_goods_name)){
													echo("<option>".$goods_list["goods_name"]."</option>");
												}
											?>
										</select></td>
										<td><input type="text" name="bags[]" class="user_box"></td>
										<td><input type="text" name="quantity[]" class="user_box" onblur="verifyQuantity()" id="quantity" required></td>
										<td><input type="text" name="rate_per_unit[]" class="user_box" onblur="verifyRate()" id="rate" required></td>
										<td><input type="button" name="A" id="add" value="Add" class="user_box" style="padding:2px 17px"></td>
									</tr>
								</table> 
								
						   </div>
						   <div class="col-md-12 text-center homepage_submit_btn">
								<input type="submit" value="submit" name="submit" id="homepage_submit" class="submit_btn" onClick="verifyFunction()" >
								<a ></a>
						   </div>
						   <?php 
						    if(isset($_POST['submit'])){
								$_POST['bill_count']=0;
								$_POST['total']="0";
								$dd = $_POST['date_load'];
								$datesplit = explode('-', $dd);
								$year = $datesplit[0];
								$month = $datesplit[1];
								$day = $datesplit[2];
								$loaded_date = $day."-".$month."-".$year;
								$_SESSION['date'] = $loaded_date;
								$load_entry_date = date("Y/m/d");
								$split_entry_date=explode('/', $load_entry_date);
								$current_year = $split_entry_date[0];
								$current_month = $split_entry_date[1];
								$current_date = $split_entry_date[2];
								if($month>=04){
									$business_year= $year."-".($year+1);
									$bill_number= 0;
								}
								else{
									//$bill_number = 269;
									$business_year = ($year-1)."-".$year;
								}
								if(strlen($_POST['conformation_no'])==0){
									$_POST['conformation_no'] = NULL;
								}
								if(strlen($_POST['e_way_bill_no'])==0){
									$_POST['e_way_bill_no'] = NULL;
								}
								if(strlen($_POST['truck_freight'])==0){
									$_POST['truck_freight'] = 0;
								}
								if($_POST['CGST']==""){$_POST['CGST']=0;}
								if($_POST['SGST']==""){$_POST['SGST']=0;}
								if($_POST['IGST']==""){$_POST['IGST']=0;}

								
								$last_bill_no="SELECT * FROM bill_details WHERE bill_no=(SELECT max(bill_no) FROM bill_details WHERE business_year='$business_year')";
								

								$bill_no_result = mysqli_query($conn,$last_bill_no);
									while($row=mysqli_fetch_assoc($bill_no_result)){
										$bill_number=$row["bill_no"];
									}
									++$bill_number;
								$vehicleno=strtoupper($_POST['vehicle_no']);
								$bill_add_details="INSERT INTO bill_details VALUES('$_POST[bill_count]','$bill_number','$_POST[company]','$_POST[conformation_no]','$_POST[e_way_bill_no]','$vehicleno','$_POST[transport_name]','$_POST[truck_freight]','$_POST[total]',($_POST[CGST]*10),($_POST[SGST]*10),($_POST[IGST]*10),'$_POST[date_load]', '$load_entry_date', '$business_year')";
								if(!mysqli_query($conn,$bill_add_details)){
									
									die('ERROR.'.mysqli_error());
								}
								
								$_SESSION['companyname']= $_POST["company"];
								$get_bill_no="SELECT * FROM bill_details WHERE bill_no=(SELECT max(bill_no) FROM bill_details WHERE business_year ='$business_year')";
									/*if(!mysqli_query($conn,$get_bill_no)){
										
										die('ERROR.'.mysqli_error());
									}
									else{}*/
									$result = mysqli_query($conn,$get_bill_no);
									while($row=mysqli_fetch_assoc($result)){
										$bill_count = $row["bill_count"];
										$bill_number=$row["bill_no"];
										$igst_number = $row["igst"];
										$cgst_number = $row["cgst"];
										$sgst_number = $row["sgst"];

									}
									$_SESSION['recent_bill_count'] = $bill_count; 
									$goods = $_POST['description_goods'];
									$bags = $_POST['bags'];
									$quantity = $_POST['quantity'];
									$rate = $_POST['rate_per_unit'];
									//hsn code is initialised as one later value updated
									$hsncode = 1;
									$sub_bilno=$bill_number;
									$increment=1;
									$_POST['is_deleted']="0";
									foreach($goods as $key => $value){
										if(($cgst_number!=0)||($sgst_number!=0)||($igst_number!=0)){
											$load_d = "INSERT INTO load_details VALUES('$bill_count','$bill_number','$bill_count.$sub_bilno.$increment','".$value."','$hsncode','".$bags[$key]."','".round($quantity[$key], 3)."','".round($rate[$key], 5)."', round($quantity[$key], 3)*(round($rate[$key], 5)*1000),'$_POST[is_deleted]')";
										}
										else{
										$load_d = "INSERT INTO load_details VALUES('$bill_count','$bill_number','$bill_count.$sub_bilno.$increment','".$value."','$hsncode','".$bags[$key]."','".$quantity[$key]."','".$rate[$key]."',$quantity[$key]*($rate[$key]*1000),'$_POST[is_deleted]')";
										}
										++$increment;
										if(!mysqli_query($conn,$load_d))
											{
												die('ERROR.'.mysqli_error());
											}
									}
									$get_material_name = mysqli_query($conn,"SELECT good_name FROM load_details WHERE bil_count= $bill_count");
									$arrays = array();
									while($row = mysqli_fetch_assoc($get_material_name)){
										$arrays[] = $row;
									}
									//hsn code updated here
									$count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM load_details WHERE bil_count=$bill_count"));
									$i = 0;
									$z = 0;
									for($x = 0; $x<=$count-1; $x++){
										foreach($arrays[$x] as $goods){
											$y[$i] = $goods;
											$i++;
										}
										$get_hsncode=mysqli_query($conn,"SELECT * FROM goods_details WHERE goods_name= '$y[$z]'");
										while($rows=mysqli_fetch_assoc($get_hsncode)){
											$hsn_code = $rows["hsn_code"];
											
											mysqli_query($conn, "UPDATE load_details SET hsn_code='$hsn_code' WHERE good_name='$y[$z]'");
										}
									$z++;
									}
									$over_all_total=0;
									$get_each_load_total=mysqli_query($conn, "SELECT current_total FROM load_details WHERE bil_count=$bill_count");
									while($get_each_total=mysqli_fetch_assoc($get_each_load_total)){
										$over_all_total = $over_all_total+$get_each_total['current_total'];
									}
									$get_truck_freight = mysqli_query($conn,"SELECT * FROM bill_details WHERE bill_count=$bill_count");
									while($truck_freight=mysqli_fetch_assoc($get_truck_freight)){
										$over_all_total -= $truck_freight["truck_freight"];
									}
									$total = round($over_all_total);

									if(($cgst_number!=0)||($sgst_number!=0)||($igst_number!=0)){
										
										$cgst_number = $cgst_number/10;
										$sgst_number = $sgst_number/10;
										$igst_number = $igst_number/10;
										$value_cgst = $total*($cgst_number/100);
										$value_sgst = $total*($sgst_number/100);
										$value_igst = $total*($igst_number/100);
										$over_all_total += $value_cgst+$value_sgst+$value_igst;
									}

									$grand_total = "UPDATE bill_details SET total=round($over_all_total) WHERE bill_count=$bill_count";
									if(!mysqli_query($conn,$grand_total)){
									   die('ERROR.'.mysqli_error());
									}	
							}
						   ?>
						</form>
					</div>
				</div>
			</div>
		</div>
</body>