<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--ÒýÓÃ°Ù¶ÈµØÍ¼API-->
    <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=QVqzT3FVgSPnGzNYFbSZwHSHGlG8Sk8r&s=1"></script>
  </head>
  
  <body>
    <!--°Ù¶ÈµØÍ¼ÈÝÆ÷-->
    <div style="width:100%;height:750px;border:#ccc solid 1px;font-size:12px" id="map"></div>
  </body>
  <script type="text/javascript">
    //´´½¨ºÍ³õÊ¼»¯µØÍ¼º¯Êý£º
    function initMap(){
      createMap();//´´½¨µØÍ¼
      setMapEvent();//ÉèÖÃµØÍ¼ÊÂ¼þ
      addMapControl();//ÏòµØÍ¼Ìí¼Ó¿Ø¼þ
      addMapOverlay();//ÏòµØÍ¼Ìí¼Ó¸²¸ÇÎï
    }
    function createMap(){ 
      map = new BMap.Map("map"); 
      map.centerAndZoom(new BMap.Point(120.116085,30.272122),15);
    }
    function setMapEvent(){
      map.enableDragging();
      map.enableDoubleClickZoom()
    }
    function addClickHandler(target,window){
      target.addEventListener("click",function(){
        target.openInfoWindow(window);
      });
    }
    function addMapOverlay(){
      var markers = [
        {content:"浙江省杭州市西溪路525号",title:null,imageOffset: {width:-46,height:-21},position:{lat:30.273494,lng:120.117091}}
      ];
      for(var index = 0; index < markers.length; index++ ){
        var point = new BMap.Point(markers[index].position.lng,markers[index].position.lat);
        var marker = new BMap.Marker(point,{icon:new BMap.Icon("http://api.map.baidu.com/lbsapi/createmap/images/icon.png",new BMap.Size(20,25),{
          imageOffset: new BMap.Size(markers[index].imageOffset.width,markers[index].imageOffset.height)
        })});
        markers[index].title="航桓科技总部<br>0571-87333760";
        var label = new BMap.Label(markers[index].title,{offset: new BMap.Size(25,5)});
        var opts = {
          width: 1000,
          title: markers[index].title,
          enableMessage: false
        };
        var infoWindow = new BMap.InfoWindow(markers[index].content,opts);
        marker.setLabel(label);
        addClickHandler(marker,infoWindow);
        map.addOverlay(marker);
      };
    }
    //ÏòµØÍ¼Ìí¼Ó¿Ø¼þ
    function addMapControl(){
      var scaleControl = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
      scaleControl.setUnit(BMAP_UNIT_IMPERIAL);
      map.addControl(scaleControl);
      var navControl = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
      map.addControl(navControl);
      var overviewControl = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:true});
      map.addControl(overviewControl);
    }
    var map;
      initMap();
  </script>

</html>