var options = [];
var auth = false;
var srg_array = [];
$(document).ready(function () {
	var arry_url = '/zabbix/custom/srg.array?'+Math.random();
	$.ajax({url:arry_url,
		dataType:'json',
		success:function(vals){
			srg_array = vals;
		},async:false
	})
	

	var data = [];	
	var murl = '/zabbix/custom/srg.json?'+Math.random();
	$.ajax({
			url:murl,
			dataType:'json',
			success: function(val) {
				var vals = [];
				for (v = 0 ; v < val.length ; v ++){
					if (srg_array.indexOf(parseInt(val[v].id))>-1)
						vals.push(val[v]);
				}
				//console.log(vals);
				var num = vals.length;
				for (i=0;i<num;i++){
				   
					
				   //if (srg_array.indexOf(parseInt(vals[i].id))>-1){
					newElDiv = $('<div>').addClass('col-3 col-xs-6 col-sm-4 col-lg-2 col-md-3 myDiv').attr({
											'parentId':vals[i].id,
											'parentName':vals[i].name,
											'align':'center',
											'name':vals[i].id,
											'url':vals[i].url
											});
					newEl = $('<div>').addClass('tableDiv').attr({
											'parentId':vals[i].id,
											'parentName':vals[i].name,
											'align':'center'
											});
											
					newElDiv.appendTo($('#mainDiv'));
					newTextSpan = $('<div>').addClass('newText').html('<a name="'+vals[i].id+'"></a>'+vals[i].name).attr('align','center')
					newTextSpan.appendTo($('.myDiv[parentId="'+vals[i].id+'"]'));
					
					newLight = $('<div>').addClass('light').attr({
											'parentId':vals[i].id,
											'parentName':vals[i].name,
											'align':'center'
											});
					newLight.appendTo($('.myDiv[parentId="'+vals[i].id+'"]'));
						
					newRed = $('<span>').addClass('green active');
					newRed.appendTo($('.light[parentId="'+vals[i].id+'"]'));


					try{
						$('.myDiv[parentId="'+vals[i].id+'"]').bind('click',function(){
							try{
								//location.href = $(this).attr('url');
							}catch(e){}
						})
					}catch(e){}
					
					
					newEl.appendTo($('#mainDiv>div[parentId="'+vals[i].id+'"]'));
					

					newBtn = $('<button>').addClass('btn btn-default btn-sm').attr({
											'parentId':vals[i].id,
											'parentName':vals[i].name,
											'align':'center',
											'url':vals[i].url,
											'data-toggle':'modal',
											'data-target':'#myModal'
											}).html('Информация о сервисе');
					newBtn.appendTo($('.myDiv[parentId="'+vals[i].id+'"]'));
					
					$('.btn[parentId="'+vals[i].id+'"]').bind('click',function(){
							checkHtml = '';
							cUrl = '/zabbix/profile.php?'+Math.random();
							$.ajax({
									url:cUrl,
									async:false,
									dataType:'html',
									success: function(html) {
										if (html.split('ОШИБКА: Нет прав доступа к запрашиваемому объекту или он не существует!').length>1){
											auth=false;
										}else{
											auth=true;
										}
									}
							})

						if (auth){
							$('#myModalLabel').html('Информация о сервисе "'+$(this).attr('parentName')+'"');
							newFrame = $('<iframe>').attr({
								'src':'/zabbix/'+$(this).attr('url')+'&'+Math.random(),
								'align':'center'
							}).html('Iframes Disabled').css({
								'width':'100%',
								'height':'600px',
								'overflow-y':'hidden'
							})
							$('#myFrame').html('');
							newFrame.appendTo($('#myFrame'));
						}else{
						$('#myModalLabel').html('Пожалуйста авторизуйтесь и закройте всплывающее окно');
							newFrame = $('<iframe>').attr({
								'src':'/zabbix/index.php',
								'align':'center'
							}).html('Iframes Disabled').css({
								'width':'100%',
								'height':'620px',
								'overflow-y':'hidden'
							})
							$('#myFrame').html('');
							newFrame.appendTo($('#myFrame'));
						}

					})
					options.push(addOption(150,vals[i].name,'.tableDiv[parentId="'+vals[i].id+'"]'));
				   //}
				}
				options.forEach(function(e){
					bindOpt(e);
				});
				req();
				goDance();
			}
	})
	$( document ).on( "click", "a[href]", function(){
		try{
			link = $(this).attr('href').split('#')[1];
			$('a[name='+link+']').parent().effect("highlight", {'color':'#B24926'}, 10000);
			//console.log($('a[name='+link+']').parent());
		}catch(e){}
	} )
	
	
});


function addOption(he,ti,bi){
	newObject = {};
	newObject.option = {
        chart: {
            type: 'solidgauge',
            height: he			
        },
        title: ti,
        pane: {
            center: ['50%', '50%'],
            size: '100%',
            startAngle: -90,
            endAngle: 90,
            background: {
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                innerRadius: '60%',
                outerRadius: '100%',
                shape: 'arc'
            }
        },
        tooltip: {
            enabled: false
        },
        yAxis: {
            stops: [
                [0.1, '#DF5353'], // red
                [0.5, '#DDDF0D'], // yellow
                [0.9, '#55BF3B'] // green
            ],
            lineWidth: 0,
            minorTickInterval: null,
            tickPixelInterval: 400,
            tickWidth: 0,
            title: {
                y: -70
            },
            labels: {
                y: 16
            }
        },
        plotOptions: {
            solidgauge: {
                dataLabels: {
                    y: 25,
                    borderWidth: 0,
                    useHTML: true,
					style: {
                        fontWeight: 'normal'
                    }
		}
	    }
    	}
    };
	newObject.bind = bi;
	return newObject;
}	

function bindOpt(opt){	
	$(opt.bind).highcharts(Highcharts.merge(opt.option, {
        yAxis: {
            min: 80,
            max: 100,
            title: {
            }
        },

        credits: {
            enabled: false
        },

        series: [{
            data: [100],
            dataLabels: {
                format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.2f}</span><br/>' +
                       '<span style="font-size:12px;color:silver">%</span></div>'
            },
            tooltip: {
		valueSuffix: '%'
    	    }
        }]

    }));	
}	

function goDance(){
	setInterval(function () {
		req();		
	}, 60000);
}
function req(){
			var murl = '/zabbix/custom/srg.json?'+Math.random();
			$.ajax({
					url:murl,
					dataType:'json',
					success:function(val) {
						try{
							var vals = [];
                        			        for (v = 0 ; v < val.length ; v ++){
                        	                		if (srg_array.indexOf(parseInt(val[v].id))>-1)
                        	               			         vals.push(val[v]);
        		                      		}
			                                //console.log(vals);

							vals.forEach(function(m){
							    // if (srg_array.indexOf(parseInt(vals[i].id))>-1){
								newVal =/* Math.random()*20+80;*/m.value;
								chart = $('.tableDiv[parentid="'+m.id+'"]').highcharts();
								point = chart.series[0].points[0];
								point.update(newVal)
								newTr =4- m.traffic;
								$('.light[parentid="'+m.id+'"]>.active').removeClass('red').removeClass('green').removeClass('orange');
								switch(newTr){
									case 1:
										$('.light[parentid="'+m.id+'"]>.active').addClass('red');
										break;
									case 2:
										$('.light[parentid="'+m.id+'"]>.active').addClass('orange');
										break;
									case 3:
										$('.light[parentid="'+m.id+'"]>.active').addClass('green');
										break;
								}
								if (/*(newVal<85)||*/(newTr<2)){
									if ($('.errorList').children('.error[parentid="'+m.id+'"]').length==0){
										newLi = $('<li>').addClass('error').attr('parentid',m.id).html('<a href="#'+m.id+'">'+m.name+'</a>');
										newLi.appendTo($('.errorList'));
										$('.myDiv[parentid="'+m.id+'"]>.newText').css({
												'color':'red'
										})
									}
								}else{
									$('.errorList').children('.error[parentid="'+m.id+'"]').remove();
									$('.myDiv[parentid="'+m.id+'"]>.newText').css({
												'color':'#588BCA'
										})
								}
							  //  }
							})
						}catch(e){};
					}
				})
}


