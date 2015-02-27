<html>
	<head>
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<link rel="stylesheet" type="text/css" href="sdata/css/srg_adm.css">
	        <script src="sdata/js/jquery-1.11.1.min.js"></script>
		<link rel="stylesheet" type="text/css" href="sdata/css/jquery-ui.css">
		<script src ="sdata/js/jquery-ui.min.js"></script>
        	<script src="sdata/js/corner.js"></script>
        	<script src="sdata/js/srg_adm.js?v2"></script>
	</head>
	<body>
		<div class = 'main'>
		<?php if($_GET["passcode"] === "4507"  ) : ?>
			
			<table id = 'list'>
				<tr><td colspan='3' class = 'heading'>User monitored services list</td></tr>
			</table>
			<script>
				$('.main ').css('left','40%');
				var furl = '/zabbix/custom/srg.array?'+Math.random();
				var fArray = [];
				$.ajax({
					url:furl,
					async:false,
					dataType:'json',
					success:function(vals) {			   		
						fArray = vals;			
					}
				})
				var durl = '/zabbix/custom/srg.json?'+Math.random();
                                var dArray = [];
                                $.ajax({
                                        url:durl,
                                        async:false,
                                        dataType:'json',
                                        success:function(vals) {
                                                var l = vals.length;
												for(var u = 0 ; u< l ; u++){
													var obj = {};
													obj.id = vals[u].id;
													obj.name = vals[u].name;
													dArray.push(obj);
												}
                                        }
                                })
				var p = dArray.length;
				for(var o = 0 ; o< p ; o++){				
					var newTr = $('<tr/>');
					newTr.appendTo($('#list'));
					var idTd = $('<td/>').text(dArray[o].id);
					var nameTd = $('<td/>').text(dArray[o].name);
					var enabled = ((fArray).indexOf(parseInt(dArray[o].id))>-1)?'checked':'';
					var enabledTd = $('<td/>').html('<input type = "checkbox" '+  enabled  +'/>');
					idTd.appendTo($('#list').find('tr').last());
					nameTd.appendTo($('#list').find('tr').last());
					enabledTd.appendTo($('#list').find('tr').last());
				}
				var saveTr = $('<tr/>');
				saveTr.appendTo('#list');
				var saveTd = $('<td/>').attr({
					'colspan':'3',
					'id':'saveButton'
				}).text('Save').addClass('heading');
				saveTd.appendTo($('#list').find('tr').last());
				$('#saveButton').button();
                                var exitTr = $('<tr/>');
                                exitTr.appendTo('#list');
                                var exitTd = $('<td/>').attr({
                                        'colspan':'3',
                                        'id':'exitButton'
                                }).text('Exit').addClass('heading');
                                exitTd.appendTo($('#list').find('tr').last());
                                $('#exitButton').button();
				$('#exitButton').bind('click',function(){
					location.replace('umon.php');
				})					
				$('#saveButton').bind('click',function(){
					var a = [];
					$('input[type="checkbox"]').each(function(){ 
						if($(this).get(0).checked)
							a.push(parseInt($(this).parent().parent().children().eq(0).text()));
					})
				insertParam('setArray',a);
				})
			</script>

			 <?php if (isset($_GET["setArray"])) :
				try {
					file_put_contents("srg.array",   "[".($_GET["setArray"])."]"       );	
				} catch (Exception $e) {
					echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
				}	
					echo "<script>alert('Новые параметры установлены');</script>";
			  endif; ?>

		<?php else : ?>
			<table>
				<tr>
                                        <td nob = "false"  colspan="3" class = "inputPin"></td>
                                </tr>
				<tr>
					<td>1</td>
                                        <td>2</td>
                                        <td>3</td>                                
				</tr>
                                <tr>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                </tr>
                                <tr>
                                        <td>7</td>
                                        <td>8</td>
                                        <td>9</td>
                                </tr>
                                <tr>
                                        <td nob = "false"></td>
                                        <td>0</td>
                                        <td nob = "false"></td>
                                </tr>
				 <tr>
                                        <td  colspan="3" class = "checkPin">Check</td>
                                </tr>

			</table>					   		
	


			<script>
				function gup( name )
	                        {
        	                        name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
                	                var regexS = "[\\?&]"+name+"=([^&#]*)";
                        	        var regex = new RegExp( regexS );
                                	var results = regex.exec( window.location.href );
	                                if( results == null )
        	                                return null;
                	                else
                        	                return results[1];
                       		 }

				$('td').not('[nob=false]').button();
				$('td').not('[nob],.checkPin').bind('click',function(){
					$('.inputPin').text($('.inputPin').text().substr(1,3)+$(this).text());
				})
				$('.checkPin').bind('click',function(){
					insertParam('passcode',$('.inputPin').text());
				})
				$('.inputPin').text(gup('passcode')?gup('passcode').substr(0,4):'0000');
			</script>

		<?php endif; ?>
		</div>
	</body>
	<script>
		function insertParam(key, value){   		
			key = encodeURI(key); value = encodeURI(value);
			var kvp = document.location.search.substr(1).split('&');
			var i=kvp.length; var x; while(i--) 
    				{
				    x = kvp[i].split('=');
				    if (x[0]==key)
				        {
				            x[1] = value;
				            kvp[i] = x.join('=');
				            break;
				        }
				}
		       if(i<0) {kvp[kvp.length] = [key,value].join('=');}
	               document.location.search = kvp.join('&'); 
		}
		function gup( name )
			{
				name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  				var regexS = "[\\?&]"+name+"=([^&#]*)";
				var regex = new RegExp( regexS );
  				var results = regex.exec( window.location.href );
  				if( results == null )
    					return null;
  				else
    					return results[1];
			}
	</script>
</html>
