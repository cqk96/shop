//组装数据
function packageData(d)
{
	
	if(d.code=='001'){
		$("#carouselTotalPage").val(d.data[0].totalPage);
		$("#carouselItemId").html('');
		for(var i=0; i<d.data.length; i++){
			$("#carouselItemId").append("<option value='"+d.data[i]['id']+"'>"+d.data[i]['title']+"</option>");
		}
	}

	
}

//select 分页
// function getCarouselData(buttonName)
// {
// 	var carouselCurrentPage = parseInt($("#carouselCurrentPage").val());
// 	var currentType = $("#carouselType").val();
// 	var totalPage = $("#carouselTotalPage").val();

// 	if(buttonName===undefined)
// 		carouselCurrentPage = 1;
// 	else if(buttonName=='prev')
// 		carouselCurrentPage -= 1;
// 	else 
// 		carouselCurrentPage += 1;
	
// 	if(carouselCurrentPage<=0 || carouselCurrentPage>totalPage)
// 		return false;

// 	$("#carouselCurrentPage").val(carouselCurrentPage);
// 	var postParams = new Array();
// 	postParams['type'] = currentType;
// 	postParams['page'] = carouselCurrentPage;
	
// 	sendAjax(postParams,'/admin/carousel/getPageData','','packageData');

// }

$(document).ready(function(){

	if($('#carouselType').val()==3){
		$('.linkLocation').removeClass('nosee');
		$('.linkStatus').removeClass('nosee');
	}

	$('#carouselType').change(function(){
		if($('#carouselType').val()==3){
			$('.linkLocation').removeClass('nosee');
			$('.linkStatus').removeClass('nosee');
		} else {
			$('.linkLocation').addClass('nosee');
			$('.linkStatus').addClass('nosee');
			$('.link_href').val('');
		}
		$("#carouselCurrentPage").val(1);
		getCarouselData();
	});

});