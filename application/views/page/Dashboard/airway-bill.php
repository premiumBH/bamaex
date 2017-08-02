		        <?php
        $this->load->view('layout/header');
        $this->load->view('layout/container');
		?>
        <!-- BEGIN PAGE BASE CONTENT -->
       <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class=" icon-layers font-red"></i>
                            <span class="caption-subject font-red bold uppercase"> Ariway bill
                            </span>
                        </div>
                    </div>                    
                </div>
                <div style="float: left; width: 100%;">
                    <div style="float: left; width: 100%; background-color: #94e8ff; padding: 6px; border: 1px solid #ddd;">
                        <div style="width: 50%; float: left; text-align: left;">
                            <div style="padding: 10px 0;">
                                <img style="width: 200px;" src="<?=THEME?>assets/pages/img/bamaex_logo.png" alt="Logo">
                            </div>
                        </div>
                        <div style="width: 50%; float: left;">
                            <div style="float: right; width: 250px">
                                <div style="text-align: center; font-weight: bold;">Bamaex Barcode</div>
                                <div style="text-align: center; padding: 5px 0;">
                                    <img style="width: 200px; height: 60px;" src="<?=THEME?>assets/pages/img/bamaex_logo.png" alt="Logo">
                                </div>
                                <div style="text-align: center;">SO3684103 (1/5)</div>
                            </div>
                        </div>
                    </div>
                    <div style="width: 50%; float: left;">
                        <table style="border: 1px solid #ddd; border-spacing: 6px; border-collapse: separate; float: left; width: 100%">
                            <tr style="background-color: #ccc; color: #000; font-weight: bold; padding: 6px; border-spacing: 5px;">
                                <td colspan="2" style="border: 1px solid #ccc; padding: 5px;">1 FROM SHIPPER</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #f4f4f4; padding: 3px">
                                    <label>Shipper's Account No</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Shipper's Ref.</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #f4f4f4; padding: 3px">
                                    <label>From (Your Name)</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Phone Number</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #f4f4f4; padding: 3px; width: 100%;">
                                    <label>Company</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #f4f4f4; padding: 3px; width: 100%;">
                                    <label>Street Address</label>
                                    <div>
                                        <textarea style="border:none; background-color: #f4f4f4; width: 100%; height: 100px; padding: 0 5px;" name=""></textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #f4f4f4; padding: 3px">
                                    <label>City</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>State/Province</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #f4f4f4; padding: 3px">
                                    <label>Country</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Zip/Postal Code</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                        <table style="border: 1px solid #ddd; border-spacing: 6px; border-collapse: separate; float: left; width: 100%">
                            <tr style="background-color: #ccc; color: #000; font-weight: bold; padding: 6px; border-spacing: 5px;">
                                <td colspan="2" style="border: 1px solid #ccc; padding: 5px;">3. SHIPPER'S SIGNATURE &amp; AUTHORIZATION</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Shipper's Signature</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Creation Date</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Received by Bamaex.</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Date</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; border: 1px solid #f4f4f4; padding: 3px">
                                    <label>Origin</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                                <td style="text-align: center; border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Destination</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" style="border: 1px solid #f4f4f4; padding: 3px; width: 100%;">
                                    <label>Tracking Number</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 100%;">
                                    <table style="width: 100%;">
                                        <td valign="middle" style="padding: 3px">
                                            <label>Shipment Type</label>
                                        </td>
                                        <td style="padding: 3px">
                                            <div>
                                                <input type="radio" name="shipment_type" id="international" name="">
                                                <label for="international">International</label>
                                            </div>
                                        </td>
                                        <td style="padding: 3px;">
                                            <div>
                                                <input type="radio" name="shipment_type" id="domestic" name="">
                                                <label for="domestic">Domestic</label>
                                            </div>
                                        </td>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        
                        <table style="border: 1px solid #ddd; border-spacing: 6px; border-collapse: separate; float: left; width: 100%">
                            <tr style="background-color: #ccc; color: #000; font-weight: bold; padding: 6px; border-spacing: 5px;">
                                <td colspan="2" style="border: 1px solid #ccc; padding: 5px;">5. PAYMENT METHOD</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 100%;">
                                    <table style="width: 100%;">
                                        <td style="padding: 3px">
                                            <div>
                                                <input type="radio" name="shipment_type" id="shiper-account" name="">
                                                <label for="shiper-account">Shiper Account</label>
                                            </div>
                                        </td>
                                        <td style="padding: 3px;">
                                            <div>
                                                <input type="radio" name="shipment_type" id="cash-on-delivery" name="">
                                                <label for="cash-on-delivery">Cash On Dlivery</label>
                                            </div>
                                        </td>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 100%;">
                                    <table style="width: 100%;">
                                        <td style="padding: 3px">
                                            <div>
                                                <input type="radio" name="shipment_type" id="credit-card" name="">
                                                <label for="credit-card">Credit Card</label>
                                            </div>
                                        </td>
                                        <td style="padding: 3px;">
                                            <div>
                                                <input type="radio" name="shipment_type" id="credit-card-on-delivery" name="">
                                                <label for="credit-card-on-delivery">Credit Card On Dlivery</label>
                                            </div>
                                        </td>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="width: 50%; float: left;">
                        <table style="border: 1px solid #ddd; border-spacing: 6px; border-collapse: separate; float: left; width: 100%">
                            <tr style="background-color: #ccc; color: #000; font-weight: bold; padding: 6px; border-spacing: 5px;">
                                <td colspan="2" style="border: 1px solid #ccc; padding: 5px;">2. TO RECEIVER</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #f4f4f4; padding: 3px">
                                    <label>Receiver's Account No</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Receiver's Ref.</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #f4f4f4; padding: 3px">
                                    <label>From (Your Name)</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Phone Number</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #f4f4f4; padding: 3px; width: 100%;">
                                    <label>Company</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #f4f4f4; padding: 3px; width: 100%;">
                                    <label>Street Address</label>
                                    <div>
                                        <textarea style="border:none; background-color: #f4f4f4; width: 100%; height: 100px; padding: 0 5px;" name=""></textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #f4f4f4; padding: 3px">
                                    <label>City</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>State/Province</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #f4f4f4; padding: 3px">
                                    <label>Country</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Zip/Postal Code</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <table style="border: 1px solid #ddd; border-spacing: 6px; border-collapse: separate; float: left; width: 100%">
                            <tr style="background-color: #ccc; color: #000; font-weight: bold; padding: 6px; border-spacing: 5px;">
                                <td colspan="2" style="border: 1px solid #ccc; padding: 5px;">4. SHIPMENT INFORMATION</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 100%;">
                                    <table style="width: 100%;">
                                        <td style="text-align: center; border: 1px solid #f4f4f4; padding: 3px;">
                                            <label>No. of Packages</label>
                                            <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                        </td>
                                        <td style="text-align: center; border: 1px solid #f4f4f4; padding: 3px;">
                                            <div>
                                                <label>Weight</label>
                                                <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                            </div>
                                        </td>
                                        <td style="text-align: center; border: 1px solid #f4f4f4; padding: 3px;">
                                            <div>
                                                <label>Chargeable Weight</label>
                                                <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                            </div>
                                        </td>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 100%;">
                                    <table style="width: 100%;">
                                        <td valign="middle" style="padding: 3px">
                                            <label>Packaging</label>
                                        </td>
                                        <td style="padding: 3px">
                                            <div>
                                                <input type="radio" name="shipment_type" id="document" name="">
                                                <label for="document">Document</label>
                                            </div>
                                        </td>
                                        <td style="padding: 3px;">
                                            <div>
                                                <input type="radio" name="shipment_type" id="none-document" name="">
                                                <label for="none-document">None Document</label>
                                            </div>
                                        </td>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border: 1px solid #f4f4f4; padding: 3px; width: 100%;">
                                    <label>Description of Goods</label>
                                    <div>
                                        <input name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;" type="text">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #f4f4f4; padding: 3px">
                                    <label>Remarks</label>
                                    <div>
                                        <textarea name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 50px; padding: 0 5px;" type="text"></textarea> 
                                    </div>
                                </td>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Value (Currency)</label>
                                    <div>
                                        <input name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;" type="text">
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <table style="border: 1px solid #ddd; border-spacing: 6px; border-collapse: separate; float: left; width: 100%">
                            <tr style="background-color: #ccc; color: #000; font-weight: bold; padding: 6px; border-spacing: 5px;">
                                <td colspan="2" style="border: 1px solid #ccc; padding: 5px;">6. DUTIES &amp; TAXES</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 100%;">
                                    <table style="width: 100%;">
                                        <td style="padding: 3px">
                                            <div>
                                                <input type="radio" name="shipment_type" id="default-to-shiper-account" name="">
                                                <label for="default-to-shiper-account">Default to Shiper Account</label>
                                            </div>
                                        </td>
                                        <td style="padding: 3px;">
                                            <div>
                                                <input type="radio" name="shipment_type" id="bill-receiver" name="">
                                                <label for="bill-receiver">Bill Receiver</label>
                                            </div>
                                        </td>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 100%;">
                                    <table style="width: 100%;">
                                        <td colspan="2" style="padding: 3px;">
                                            <div>
                                                <input type="radio" name="shipment_type" id="bil-third-party-ac" name="">
                                                <label for="bil-third-party-ac">Bill 3rd party Approved Acc. APP A/C No</label>
                                            </div>
                                        </td>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table style="border: 1px solid #ddd; border-spacing: 6px; border-collapse: separate; float: left; width: 100%">
                            <tr style="background-color: #ccc; color: #000; font-weight: bold; padding: 6px; border-spacing: 5px;">
                                <td colspan="2" style="border: 1px solid #ccc; padding: 5px;">7. SERVICES</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 100%;">
                                    <table style="width: 100%;">
                                        <td style="padding: 3px">
                                            <div>
                                                <input type="radio" name="shipment_type" id="insurance" name="">
                                                <label for="insurance">Insurance</label>
                                            </div>
                                        </td>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table style="border: 1px solid #ddd; border-spacing: 6px; border-collapse: separate; float: left; width: 100%">
                            <tr style="background-color: #ccc; color: #000; font-weight: bold; padding: 6px; border-spacing: 5px;">
                                <td colspan="2" style="border: 1px solid #ccc; padding: 5px;">8.RECEIVER'S SIGNATURE</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #f4f4f4; padding: 3px">
                                    <label>Receiver's Signature</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                                <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                    <label>Date</label>
                                    <div>
                                        <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 100%;">
                                    <table style="width: 100%;">
                                        <td style="border: 1px solid #f4f4f4; padding: 3px;">
                                            <label>Received By</label>
                                            <div>
                                                <input type="text" name="" style="border:none; background-color: #f4f4f4; width: 100%; height: 30px; padding: 0 5px;">
                                            </div>
                                        </td>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="float: left; width: 100%; margin: 20px 0;">
                        <div style="text-align: center; font-weight: bold;">
                            <p>To track your shipment go to the Fetchr website at fetchr.us or directly<br>to m.fetchr.us/track_order and enter your tracking number.</p>
                        </div>
                        <div style="text-align: center; font-weight: bold; margin: 15px 0;">
                            <strong>CONDITIONS OF CARRIAGE</strong>
                        </div>                        
                        <div style="width: 47%; float: left;">
                            <p>
                                In tendering the shipment for carriage, the customer agrees to these terms and conditions of carriage and that this air bill is NON-NEGOTIABLE and has been prepared by the
                                customer or on the customer\'s behalf by FETCHR. As used in these conditions, FETCHR includes FETCHR CO. LTD, all operating divisions and subsidiaries of MENA 360 DWC LLC. and
                                their respective agents, servants, officers and employees. 1. SCOPE OF CONDITIONS These conditions shall govern and apply to all services provided by FETCHR, BY SIGNING THIS
                                AIRBILL, and THE CUSTOMER ACKNOWLEDGES THAT HE/SHE HAS READ THESE CONDITIONS AND AGREES TO BE BOUND BY EACH OF THEM. FETCHR shall not be bound by any
                                agreement which varies from these conditions, unless such agreement is in writing and signed by an authorized officer of FETCHR. In the absence of such written agreement, these
                                conditions shall constitute the entire agreement between FETCHR and each of its customers. No employee of FETCHR shall have the authority to alter or waive these terms and
                                conditions, except as stated herein.
                                2. FETCHR\'S OBLIGATIONS FETCHR agrees, subject to payment of applicable rates and charges in effect on the date of acceptance by FETCHR of a customer\'s shipment, to arrange
                                for the transportation of the shipment between the locations agreed upon by FETCHR and the customer. FETCHR reserves the right to transport the customer\'s shipment by any route
                                and procedure and by successive carriers and according to its own handling, storage and transportation methods.
                                3. SERVICE RESTRICTION
                                a) FETCHR reserves the right to refuse any documents or parcels from any person, firm, or company at its own discretion.
                                b) FETCHR reserves the right to abandon carriage of any shipment at any time after acceptance when such shipment could possibly cause damage or delay to other shipments,
                                equipment or personnel, or when any such carriage is prohibited by law or is in violation of any of the rules contained herein.
                                c) FETCHR reserves the right to open and inspect any shipment consigned by a customer to ensure that it is capable of carriage to the state or country of destination within the
                                standard customs procedures and handling methods of FETCHR. In exercising this right, FETCHR does not warrant that any particular item to be carried is capable of carriage, without
                                infringing the law of any country or state through which the item may be carried.
                            </p>
                        </div>
                        <div style="width: 47%; float: right;">
                            <p>
                                In tendering the shipment for carriage, the customer agrees to these terms and conditions of carriage and that this air bill is NON-NEGOTIABLE and has been prepared by the
                                customer or on the customer\'s behalf by FETCHR. As used in these conditions, FETCHR includes FETCHR CO. LTD, all operating divisions and subsidiaries of MENA 360 DWC LLC. and
                                their respective agents, servants, officers and employees. 1. SCOPE OF CONDITIONS These conditions shall govern and apply to all services provided by FETCHR, BY SIGNING THIS
                                AIRBILL, and THE CUSTOMER ACKNOWLEDGES THAT HE/SHE HAS READ THESE CONDITIONS AND AGREES TO BE BOUND BY EACH OF THEM. FETCHR shall not be bound by any
                                agreement which varies from these conditions, unless such agreement is in writing and signed by an authorized officer of FETCHR. In the absence of such written agreement, these
                                conditions shall constitute the entire agreement between FETCHR and each of its customers. No employee of FETCHR shall have the authority to alter or waive these terms and
                                conditions, except as stated herein.
                                2. FETCHR\'S OBLIGATIONS FETCHR agrees, subject to payment of applicable rates and charges in effect on the date of acceptance by FETCHR of a customer\'s shipment, to arrange
                                for the transportation of the shipment between the locations agreed upon by FETCHR and the customer. FETCHR reserves the right to transport the customer\'s shipment by any route
                                and procedure and by successive carriers and according to its own handling, storage and transportation methods.
                                3. SERVICE RESTRICTION
                                a) FETCHR reserves the right to refuse any documents or parcels from any person, firm, or company at its own discretion.
                                b) FETCHR reserves the right to abandon carriage of any shipment at any time after acceptance when such shipment could possibly cause damage or delay to other shipments,
                                equipment or personnel, or when any such carriage is prohibited by law or is in violation of any of the rules contained herein.
                                c) FETCHR reserves the right to open and inspect any shipment consigned by a customer to ensure that it is capable of carriage to the state or country of destination within the
                                standard customs procedures and handling methods of FETCHR. In exercising this right, FETCHR does not warrant that any particular item to be carried is capable of carriage, without
                                infringing the law of any country or state through which the item may be carried.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                            <?php
        $this->load->view('layout/footer');   
        ?>