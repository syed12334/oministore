<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Guest Payment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style type="text/css">
        ::selection {
            background-color: #E13300;
            color: white;
        }

        ::-moz-selection {
            background-color: #E13300;
            color: white;
        }
    </style>
</head>

<body onload="openPay()">

    <button type="button">Processing payment please wait.....</button>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!--Atom payment cdn-->
    <script src="https://pgtest.atomtech.in/staticdata/ots/js/atomcheckout.js"></script>
    
    <script>
        function openPay() {
            const options = {
                "atomTokenId":<?= $atomTokenId; ?>,
                "merchId": "317156",
                "custEmail": "<?= $email; ?>",
                "custMobile":  <?= $phone; ?>,
                "returnUrl": "<?= base_url().'orderguestpayment';?>"
            }
            let atom = new AtomPaynetz(options, 'uat');
        }

        window.addEventListener('message', ({ data }) => {

      console.log(data);

     if(data === "cancelTransaction") {
       $.ajax({
            url:"<?= base_url().'guest/orderCancelled';?>",
            method:"post",
            data:{
                status :3,
                oid:<?= $oid;?>
            },
            dataType:"json",
            success:function(data) {
                if(data.status ==true) {
                   // window.location.href="<?= base_url().'guest';?>";
                }
            }
       });

      }

    });
    </script>
</body>

</html>
