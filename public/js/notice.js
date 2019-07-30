
	var audioObj = document.getElementById('noticeAudio');
	//定时设置ajax请求
	$(function(){
		
		setInterval('startTime()',2000);

	});

	function startTime()
	{
		$.get('/api/v1/messages/timeRange?userId=1',function(data){
			if(data['data']!="null"){
				window.data = data['data'];
				checkData();
			}
		});
	}

	function checkData(){
		for(var key in window.data){
			audioObj.play();
			popPcNotice(window.data[key]['content']);
			window.data.shift();
		}
	}

	/**
	* 收到新工单的弹窗
	*/
	function popPcNotice(content){
		layer.open({
			title:'您有新的消息,请尽快查看',
			btn: ['好的'],
			bt1: function(index, layero){
				layero.close(index);
			},
			content:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+content,
		    type: 1,
		    area:['300px','120px'],
		    offset:'r',
		   	btnAlign:'c',
		   	closeBtn: 0,
		   	shade:0,
		   	time:10000,
		   	anim:2
	 	});
	 	
	}
