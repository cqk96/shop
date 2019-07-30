<?php include_once('../app/views/admin/_header.php') ?>
<link rel="stylesheet" type="text/css" href="/js/tools/layer/skin/default/layer.css">
<style type="text/css">
	body { background-color: #FFF; width: 100%; height: 100%; overflow-x: hidden; }
	.body-container {width: 100%; height: 100%; }

	.title { font-size: 20px; font-weight: bold; color: #333333; padding-left: 12px; padding-top: 30px; padding-bottom: 15px; border-bottom: 4px solid #eceeff; margin-bottom: 11px; }
	.news-lists-box {width: 100%;}
	.each-list-box { width: 100%; font-size: 12px; font-weight: bold; color: #666666; padding-bottom: 11px; padding-top: 11px; padding-right: 82px; line-height: 1.5; padding-left: 12px; border-bottom: 1px solid #ECECEC; position: relative; }
	.list-pointer {display: inline-block; width: 4px; height: 4px; background-color: #000000; border-radius: 100%;  vertical-align: middle; margin-right: 12px;  }

	.operation-bar { margin-top: 10px; }

	a { text-decoration: none; }
	a:active{ text-decoration: none; }
	a:hover{ text-decoration: none; }

	.pagination-active { color: #FFF !important; border-color: #4D5AFF; background-color: #4D5AFF; }

	.pagination-row {font-size: 12px; }
	.total-page-box { color: #999999; font-weight: bold; }
	.pagination-box {}
	#totalPage { color: #4D5AFF; margin-left: 5px; margin-right: 5px; }

	.pagination-btn,.each-page { padding: 5px 12px; color: #999999; border-top: 1px solid #ECECEC; border-left: 1px solid #ECECEC; border-bottom: 1px solid #ECECEC; }
	.each-page:hover,.pagination-btn:hover  {cursor:  pointer; }
	.pagination-end-btn { border-right: 1px solid #ECECEC; }
	.time-box { float: right; font-size: 12px; color: #999999; font-weight: bold; position: absolute;
    top: 11px; right: 0px; }

	.rest-30 { width: 100%; height: 30px; }
	.rest-40 { width: 100%; height: 40px; }

	.return-btn {float: right; margin-right: 14px; }
</style>
<body>
	<div class="body-container">
		<input type="hidden" id="cid" value="<?php echo empty($_GET['cid'])? 0:$_GET['cid']; ?>">
		<div class="title">
			<?php echo $data['class_name']; ?>

			<!-- 返回按钮-->
			<a class="btn btn-default btn-sm return-btn" onclick="history.back();">返回</a>
		</div>

		<ul id="newsLists" class='news-lists-box'>

		</ul>

		<div class="rest-30"></div>

		<div class="operation-bar">
			<div class="row">
				<div class="col-sm-3">
					
				</div>

				<div class="col-sm-9">
					<div class="row pagination-row">
						<div class="col-sm-2 total-page-box">
							共<span id="totalPage">  </span>页
						</div>
						<div  id="pagination" class="col-sm-10">

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</body>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript" src="/js/tools/layer/layer.js"></script>
<script type="text/javascript">
<!-- 提示信息 -->
function autoMessageNotice(content)
{
	var time = arguments[1]? arguments[1]:2000;
	layer.open({
	    content: content,
	    skin: 'msg',
	    time: time //2m秒后自动关闭
	});
}

function getLists(some)
{
	if(some=="prev"){
		if(curPage-1>=1){
			curPage = curPage-1;
			getData(curPage, defaultSize);
		}
	} else if(some=="next"){
		if(curPage+1<=totalPage){
			curPage = curPage+1;
			getData(curPage, defaultSize);
		}
	} else {
		if(some!=curPage){
			curPage = some;
			getData(curPage, defaultSize);
		}
	}
}

function getData (page, size)
{
	
	var id = $("#cid").val().trim();
	if(id==0){
		return false;
	}

	$.ajax({
		url: "/front/api/v1/newsClass/news/more?id="+id+"&page="+page+"&size="+size,
		type: "GET",
		dataType: "JSON",
		async: false,
		success: function(response){
			
			if(response['status']['success']){
				if(response['data']['data']!=null) {

					totalPage = response['data']['pageCount'];

					$("#totalPage").text(totalPage);

					// 数据
					var str = '';
					for(var i=0; i<response['data']['data'].length; i++) {
						str += "<a href='/admin/news/show?id="+response['data']['data'][i].id+"'>";
						str += 		"<li class='each-list-box'>";
						str += 			"<span class='list-pointer'></span>"+response['data']['data'][i].title;
						str += 			"<span class='time-box'>【"+response['data']['data'][i].createTime+"】</span>";
						str += 		"</li>"
						str += "</a>";
					}

					$("#newsLists").html(str);

					// 分页
					var str = '<div class="pagination-box"><span class="pagination-btn" onclick="getLists(\'prev\')">|<</span>';
					for(var i=1; i<=totalPage; i++){
						var activeClass = i==curPage? 'pagination-active':'';
						str += "<span class='each-page "+activeClass+"' onclick='getLists("+i+")'>"+i+"</span>";
					}
					str += '<span class="pagination-btn pagination-end-btn " onclick="getLists(\'next\')">>|</span></div>';

					$("#pagination").html(str);

				}
			} else {
				autoMessageNotice(response['status']['message']);
			}
		}
	});

}
$(document).ready(function(){
	window.parent.$(".main-container").css("backgroundColor", "#FFF");

	curPage = 1;
	defaultSize = 10;
	totalPage = 0;

	getData(curPage, defaultSize);

});
window.onbeforeunload = function(){
	window.parent.$(".main-container").css("backgroundColor", "#f5f5f5");
}
</script>