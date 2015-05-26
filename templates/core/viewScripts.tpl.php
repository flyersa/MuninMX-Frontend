					var lurl;
					var lframe;
					var lstype;
					function trackLog(url,frame)
					{
						lurl = url;
						lframe = frame;						
						console.log("added " + url + " with frame " + frame + " to tracklog");
						return false;
					}
					
					function alertTrackLog()
					{
						alert(lurl);
						return false;
					}
		
					function popOutFrame(frame,url)
					{
						if(lframe != frame)
						{
							//alert(url+"&stype="+lstype);	
							window.open(url);
						}	
						else
						{
							//alert(lurl+"&stype="+lstype);
							window.open(lurl+"&stype="+lstype);
						}	
					}
		
					function reloadFrameWithType(frame,stype,node,plugin)
					{
						console.log("received rerender trigger for frame: " + frame + " with type: " + stype + " and url: " + lurl);
						if(lframe != frame)
						{
							// render 24 hours
							 $("#"+frame).attr("src", "graph.php?node="+node+"&plugin="+plugin+"&period=24h&stype="+stype);
							 lstype = stype;
							 lurl = "graph.php?node="+node+"&plugin="+plugin+"&period=24h&stype="+stype;
						}
						else
						{
							$("#"+frame).attr("src", lurl + "&stype="+stype);	
							lstype = stype;
						}
						lframe = frame;
						return true;
					}
			
			function getFrameUri(frame)
			{
				alert($(frame).attr("src"));
			}
			function loadnew(url,frame,field)
			{
				var date = $( "#"+field ).val();
				var dateObject = $('#'+field).datepicker("getDate");
				var dateString = $.datepicker.formatDate("dd.mm.yy", dateObject);
				window.frames[frame].location = url + "&day="+dateString;
			}
			
			function loadnewframe(url,frame)
			{
				window.frames[frame].location = url;
			}				