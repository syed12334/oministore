<?php
	if(count($franchise) >0) {
		?>
		         <table class="table table-bordered">
   
    <tbody>
      <tr>
        <th>Franchise Name</th>
        <td><?= $franchise[0]->franchise_name;?></td>
        
      </tr>
      <tr>
        <th>Person Name</th>
        <td><?= $franchise[0]->name_of_person;?></td>
        
      </tr>
      <tr>
        <th>Contact Number</th>
        <td><?= $franchise[0]->contact_no;?></td>
        
      </tr>
      <tr>
        <th>Whatsapp Number</th>
        <td><?= $franchise[0]->whatsapp_no;?></td>
        
      </tr>
      <tr>
        <th>Address</th>
        <td><?= $franchise[0]->address;?></td>
        
      </tr>
      <tr>
        <th>Area</th>
        <td><?= $franchise[0]->area;?></td>
        
      </tr>
      <tr>
        <th>Subarea/Block/Phase</th>
        <td><?= $franchise[0]->subarea;?></td>
        
      </tr>
      <tr>
        <th>Street/Road</th>
        <td><?= $franchise[0]->street;?></td>
        
      </tr>
      <tr>
        <th>Gated Community/Institution</th>
        <td><?= $franchise[0]->gated_community;?></td>
        
      </tr>
      <?php
        $pin =[];
        if(count($franchise_pincode) >0) {
        	foreach ($franchise_pincode as $key => $value) {
        		$pin[] = $value->pincode;
        	}
        	$pincodes =implode(",", $pin);
        	?>
        		 <tr>
        <th>Franchise Pincodes</th>
        <td><?= $pincodes;?></td>
        
      </tr>
        	<?php
        }
      ?>
     
    
      
    </tbody>
  </table>
		<?php
	}
?>