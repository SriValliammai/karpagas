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
								$objbillinsert = new bill_details();
								if(empty($_POST['conformation_no'])){$_POST['conformation_no'] = NULL;}
								if(empty($_POST['e_way_bill_no'])){$_POST['e_way_bill_no'] = NULL;}
								if(empty($_POST['truck_freight'])){$_POST['truck_freight'] = 0;}
								if($_POST['CGST']==""){$_POST['CGST']=0;}
								if($_POST['SGST']==""){$_POST['SGST']=0;}
								if($_POST['IGST']==""){$_POST['IGST']=0;}
								$vehicleno=strtoupper($_POST['vehicle_no']);
								$_POST['total']=0;
								$load_entry_date = date("Y/m/d");
								$get_company_id = mysqli_query($conn,"SELECT company_id FROM party_details WHERE company_name='$_POST[company]'");
								if(!$get_company_id){
									die('ERROR.'.mysqli_error());
								}
								else{
									$company_id = mysqli_fetch_assoc($get_company_id);
									$comp_id = $company_id['company_id'];
								}

								$objbillinsert -> billInsert($_POST['company'],$comp_id,$_POST['conformation_no'],$_POST['e_way_bill_no'],$vehicleno,$_POST['transport_name'],$_POST['truck_freight'],$_POST['total'],($_POST['CGST']*10),($_POST['SGST']*10),($_POST['IGST']*10),$_POST['date_load']);
						    }
class bill_details{
       function billInsert($company,$compid,$conformation_no,$eway_bill,$vehicle_no,$transport_name,$truck_freigt,$total,$cgst,$sgst,$igst,$date){
            include '../connection.php';
            $entrydate = date("Y/m/d");
            $split_entry_date=explode('/', $entrydate);
            $current_year = $split_entry_date[0];
            $current_month = $split_entry_date[1];
            $current_date = $split_entry_date[2];

            if($current_month>=04){
                $business_year= $current_year."-".($current_year+1);
            }
            else{
                $business_year = ($current_year-1)."-".$current_year;
            }

                $billno_query = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM bill_details WHERE business_year='$business_year'"));
                if($billno_query > 0){
                    $last_billno_query = mysqli_query($conn,"SELECT bill_no FROM bill_details WHERE bill_no=(SELECT max(bill_no) FROM bill_details WHERE business_year='$business_year')");
                    $row=mysqli_fetch_assoc($last_billno_query);
                     $bill_number=$row["bill_no"];
                     $bill_no = ++$bill_number; 
                }
                elseif($billno_query==0){
                    $bill_no = 1;
                    // echo $bill_no;
                }
                else{
                    die('ERROR.'.mysqli_error());
                }
            /*$sql = "INSERT INTO bill_details(bill_no,company,comp_id,conformation_no,e_way_bill,vehicle_no,transport_name,truck_freight,total,cgst,sgst,igst,date_of_load,bill_entry_date,business_year) VALUES($bill_no,'$company',$compid,'$conformation_no','$eway_bill','$vehicle_no','$transport_name',$truck_freigt,$total,$cgst,$sgst,$igst,'$date','$entrydate','$business_year')";*/
            $bill_insert_query = mysqli_query($conn,"INSERT INTO bill_details(bill_no,company,comp_id,conformation_no,e_way_bill,vehicle_no,transport_name,truck_freight,total,cgst,sgst,igst,date_of_load,bill_entry_date,business_year) VALUES($bill_no,'$company',$compid,'$conformation_no','$eway_bill','$vehicle_no','$transport_name',$truck_freigt,$total,$cgst,$sgst,$igst,'$date','$entrydate','$business_year')");
            if($bill_insert_query){
                echo "insetted sucessfully";
            }
            else{
                echo "error";
            }


       }
       function billEdit(){

       }
   }
						</form>
					</div>
				</div>
			</div>
		</div>
</body>
