$(document).ready(function(){
        
        $('.one').click();
        
        $('.button-next').click(function()
        {
            obj = $('a[aria-expanded=true]');
            current = obj.attr('class').split(' ')[1];
            if(current == 'one')
            {
                $('.two').click();
            }
            else if(current == 'two')
            {
                $('.three').click();
            }
            else if(current == 'three')
            {
                $('.four').click();
            }
            else if(current == 'four')
            {
                $('.one').click();
            }
            
            //aria-expanded
        });
        $('.button-previous').click(function()
        {
            obj = $('a[aria-expanded=true]');
            current = obj.attr('class').split(' ')[1];
            if(current == 'one')
            {
                $('.four').click();
            }
            else if(current == 'two')
            {
                $('.one').click();
            }
            else if(current == 'three')
            {
                $('.two').click();
            }
            else if(current == 'four')
            {
                $('.three').click();
            }
            
            //aria-expanded
        });
        
        
	$('.hiddeen-div').hide();
	$('.hiddeen-div1').hide();
	$('.hiddeen-div2').hide();
//	$('.hiddeen-div3').hide();
	$('.hiddeen-div0').hide();
	$('#addSenderfield').change(function(){
		$('.hiddeen-div0').hide();
		$('#' + $(this).val()).show();
	});
	$('#addNewfield').change(function(){
		$('.hiddeen-div').hide();
		$('#' + $(this).val()).show();
	});
        
        $('#addNewConsignment').change(function(){
		$('.hiddeen-div1').hide();
		$('#' + $(this).val()).show();
	});
        
        $('#addContact').change(function(){
		$('.hiddeen-div2').hide();
		$('#' + $(this).val()).show();
	});
        
//        $('#addPickup').change(function(){
//		$('.hiddeen-div3').hide();
//		$('#' + $(this).val()).show();
//	});
        
        $('#addNewfield').change(function(){
                if($(this).val() != "addCompany" && $(this).val() != "")
                {
                    console.log('HERE');
                    $('.hiddeen-div').hide();
                    client_id = $('input[name=client_id]').val();
                    receiver_id = $('select[name=receiver_id]').val();
                    $.ajax({
                        method: "POST",
                        url: "/Order/getReceiver",
                        data: { client_id: client_id , receiver_id : receiver_id }
                      })
                        .done(function( msg ) {
                            var arr = $.parseJSON(msg);
                            $.each(arr,function(i,val){
                                console.log(val);
                                console.log(val.id);
                                $('input[name=receiver_account_id]').val(val.account_id);
                                $('input[name=receiver_city]').val(val.city);
                                $('select[name=receiver_country_id]').val(val.country_id);
                                $('textarea[name=receiver_address]').val(val.address)
                                $('input[name=receiver_postal_code]').val(val.postal_code);
                                $('input[name=receiver_email]').val(val.email);
                                $('input[name=receiver_phone_no]').val(val.phone_no);
                                $('input[name=receiver_mobile]').val(val.mobile);
                                
                            });
                            
                        });
                }
                else
                {
                    $('input[name=receiver_account_id]').val('');
                    $('input[name=receiver_city]').val('');
                    $('select[name=receiver_country_id]').val('1');
                    $('textarea[name=receiver_address]').val('')
                    $('input[name=receiver_postal_code]').val('');
                    $('input[name=receiver_email]').val('');
                    $('input[name=receiver_phone_no]').val('');
                    $('input[name=receiver_mobile]').val('');
                }
        });
        $('#addSenderfield').change(function(){
                if($(this).val() != "addSenderCompany" && $(this).val() != "")
                {
                    $('.hiddeen-div').hide();
                    client_id = $('input[name=client_id]').val();
                    sender_id = $('select[name=sender_id]').val();
                    $.ajax({
                        method: "POST",
                        url: "/Order/getSender",
                        data: { client_id: client_id , sender_id : sender_id }
                      })
                        .done(function( msg ) {
                            var arr = $.parseJSON(msg);
                            $.each(arr,function(i,val){
                                console.log(val);
                                console.log(val.id);
                                $('input[name=sender_account_id]').val(val.account_id);
                                $('input[name=sender_city]').val(val.city);
                                $('select[name=sender_country_id]').val(val.country_id);
                                $('textarea[name=sender_address]').val(val.address)
                                $('input[name=sender_postal_code]').val(val.postal_code);
                                $('input[name=sender_email]').val(val.email);
                                $('input[name=sender_phone_no]').val(val.phone_no);
                                $('input[name=sender_mobile]').val(val.mobile);
                                
                            });
                            
                        });
                }
                else
                {
                    $('input[name=sender_account_id]').val('');
                    $('input[name=sender_city]').val('');
                    $('select[name=sender_country_id]').val('1');
                    $('textarea[name=sender_address]').val('')
                    $('input[name=sender_postal_code]').val('');
                    $('input[name=sender_email]').val('');
                    $('input[name=sender_phone_no]').val('');
                    $('input[name=sender_mobile]').val('');
                }
        });
        $('#addNewConsignment').change(function(){
                if($(this).val() != "addConsignment" && $(this).val() != "")
                {
                    client_id = $('input[name=client_id]').val();
                    consignment_id = $('select[name=consignment_id]').val();
                    $.ajax({
                        method: "POST",
                        url: "/Order/getConsignment",
                        data: { client_id: client_id , consignment_id : consignment_id }
                      })
                        .done(function( msg ) {
                            var arr = $.parseJSON(msg);
                            $.each(arr,function(i,val){
                                console.log(val);
                                console.log(val.id);
                                $('input[name=type]').val(val.type);
                                $('input[name=weight]').val(val.weight);
                                $('input[name=height]').val(val.height);
                                $('input[name=breath]').val(val.breath);
                                
                            });
                            
                        });
                }
                else
                {
                    $('input[name=type]').val('');
                    $('input[name=weight]').val('');
                    $('input[name=height]').val('');
                    $('input[name=breath]').val('');
                }
        });
        $('#addContact').change(function(){
                if($(this).val() != "addNewContact" && $(this).val() != "")
                {
                    client_id = $('input[name=client_id]').val();
                    person_id = $('select[name=person_id]').val();
                    $.ajax({
                        method: "POST",
                        url: "/Order/getContactPerson",
                        data: { client_id: client_id , person_id : person_id }
                      })
                        .done(function( msg ) {
                            var arr = $.parseJSON(msg);
                            $.each(arr,function(i,val){
                                console.log(val);
                                console.log(val.id);
                                $('input[name=contact_person_mobile]').val(val.person_mobile);
                                
                                
                            });
                            
                        });
                }
                else
                {
                    $('input[name=contact_person_mobile]').val('');
                                
                }
        });
        $('.view').fadeOut();
        $('.button-submit').click(function(){
            eventclick = $('.button-submit').attr('onclick');
            if(eventclick == "")
            {
                sender_company_name = $('input[name=sender_company_name]').val();
                if(sender_company_name == '')
                {
                    $('label[name=company1]').text($('select[name=sender_id]  option:selected').text());

                }
                else
                {
                    $('label[name=company1]').text($('input[name=sender_company_name]').val());

                }
                $('label[name=account1]').text($('input[name=sender_account_id]').val());
                $('label[name=city1]').text($('input[name=sender_city]').val());
                $('label[name=country1]').text($('select[name=sender_country_id]  option:selected').text());
                $('label[name=address1]').text($('textarea[name=sender_address]').val());
                $('label[name=postcode1]').text($('input[name=sender_postal_code]').val());
                $('label[name=email1]').text($('input[name=sender_email]').val());
                $('label[name=phone1]').text($('input[name=sender_phone_no]').val());
                $('label[name=mobile1]').text($('input[name=sender_mobile]').val());
                
//                $('label[name=company2]').text($('select[name=sender_country_id]  option:selected').text());
                sender_company_name = $('input[name=receiver_company_name]').val();
                if(sender_company_name == '')
                {
                    $('label[name=company2]').text($('select[name=receiver_id]  option:selected').text());

                }
                else
                {
                    $('label[name=company2]').text($('input[name=receiver_company_name]').val());

                }
                $('label[name=account2]').text($('input[name=receiver_account_id]').val());
                $('label[name=city2]').text($('input[name=receiver_city]').val());
                $('label[name=country2]').text($('select[name=receiver_country_id] option:selected').text());
                $('label[name=address2]').text($('textarea[name=receiver_address]').val());
                $('label[name=postcode2]').text($('input[name=receiver_postal_code]').val());
                $('label[name=email2]').text($('input[name=receiver_email]').val());
                $('label[name=phone2]').text($('input[name=receiver_phone_no]').val());
                $('label[name=mobile2]').text($('input[name=receiver_mobile]').val());
                
//                $('label[name=title]').text($('select[name=sender_country_id]  option:selected').text());
                sender_company_name = $('input[name=title]').val();
                if(sender_company_name == '')
                {
                    $('label[name=title]').text($('select[name=consignment_id]  option:selected').text());

                }
                else
                {
                    $('label[name=title]').text($('input[name=title]').val());

                }
                $('label[name=type]').text($('input[name=type]').val());
                $('label[name=weight]').text($('input[name=weight]').val());
                $('label[name=height]').text($('input[name=height]').val());
                $('label[name=breath]').text($('input[name=breath]').val());
                
//                $('label[name=name]').text($('select[name=sender_country_id]  option:selected').text());
                sender_company_name = $('input[name=contact_person_name]').val();
                if(sender_company_name == '')
                {
                    $('label[name=name]').text($('select[name=person_id]  option:selected').text());

                }
                else
                {
                    $('label[name=name]').text($('input[name=contact_person_name]').val());

                }
                $('label[name=mobile]').text($('input[name=contact_person_mobile]').val());
                
                $('label[name=date]').text($('input[name=date]').val());
                $('label[name=time]').text($('input[name=time]').val());
                
                $('.edit').fadeOut(500);
                $('.view').fadeIn(500);
                
                $('.button-submit').attr('onclick' , 'document.getElementById("form1").submit();');
                $('.button-submit').text('Confirm Order');
                
            }
        });
        
        

	$(document).on('click','.sideMenuClk',function(){

		$//('#loader').show();

		 var operation=$(this).attr('operation');

		if (operation == 'users') {

		    //alert(operation);

			$('#main').load(MAIN_CTRL+'Admin/menu/'+operation);

			$('#loader').fadeOut(2000);

    $.ajax({

      url: "http://localhost/bahrain/index.php/admin/give_more_data",

      type: "POST",

      data: { name: "article" },

      success: function(data) {

	

        $('#main').html(data);

      }

    });

		} else if(operation == 'AgentList') {

			$('#main').load(MAIN_CTRL+'admin/menu/'+operation);

		//	$('#loader').fadeOut(2000);

		}  

		//alert('Login Clk');

		//stlkr.login();

		//$('#loader').hide();

	});

	$(document).on('click','.sideAgentMenuClk',function(){

		$('#loader').show();

		var operation=$(this).attr('operation');

		if (operation == 'CreateUser') {

			$('#main').load(MAIN_CTRL+'agent/menu/'+operation);

			$('#loader').fadeOut(2000);

			//$('#main').html("Hello"); AgentList

		} else if(operation == 'UserList') {

			$('#main').load(MAIN_CTRL+'agent/menu/'+operation);

			$('#loader').fadeOut(2000);

		} 

		//alert('Login Clk');

		//stlkr.login();

		//$('#loader').hide();

	});

	$(document).on('click','.accountClk',function(){

		$('#mainContainer').load(MAIN_CTRL+'welcome/account');

		//alert('Login Clk');

		//stlkr.login();

	});
//        $('#toUser').click(function(){
//            bootbox.confirm("Are you sure ", function(result){ return result; });
//        });
        $('.suspend').on('click' , function(){
            return confirm('Are you sure you want to Suspend this Client?');
        });
        $('.toUser').on('click', function () {
            return confirm('Are you sure?');
            

//            bootbox.confirm({
//                title: "Destroy planet?",
//                message: "Do you want to activate the Deathstar now? This cannot be undone.",
//                buttons: {
//                    cancel: {
//                        label: '<i class="fa fa-times"></i> Cancel'
//                    },
//                    confirm: {
//                        label: '<i class="fa fa-check"></i> Confirm'
//                    }
//                },
//                callback: function (result) {
//                    //console.log('This was logged in the callback: ' + result);
//                    if(result)
//                        return true;
//                    else
//                        return false;
//                    
//                }
//            });
            
            
        });
//        function confirmationBox()
//        {
//            bootbox.confirm("Are you sure ", function(result){ return result; });
//        }
});