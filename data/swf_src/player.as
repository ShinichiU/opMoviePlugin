var play_flg:Boolean = false;
var drag_flg:Boolean = false;
var duration:uint = 0;

var connection:NetConnection = new NetConnection();
connection.connect(null);

var netStream:NetStream = new NetStream(connection);

var video_obj : Video = new Video();
stage.addChild(video_obj);
video_obj.x = 20;
video_obj.y = 20;
video_obj.width = 512;
video_obj.height = 384;

video_obj.attachNetStream(netStream);

var url:String = loaderInfo.parameters["url"];
netStream.play(url);
netStream.pause();
netStream.seek(0);

play_b.addEventListener(MouseEvent.CLICK,function(event){
  if(play_flg)
  {
    netStream.pause();
    play_flg = false;
  }
  else
  {
    netStream.resume();
    play_flg = true;
  }
});

stop_b.addEventListener(MouseEvent.CLICK,function(event){
  if (!play_flg)
  {
    return;
  }
  netStream.pause();
  play_flg = false;
  netStream.seek(0);
  bar.x = 117;
});

stage.addEventListener(MouseEvent.MOUSE_DOWN,function(event){
  if(!duration)
  {
    return;
  }
  if (stage.mouseX < 117 || stage.mouseX > 317 || stage.mouseY < 424 || stage.mouseY > 444)
  {
    return;
  }

  var rect = new Rectangle(117, 424, 200, 0);
  bar.startDrag(true, rect);
  drag_flg = true;
});

stage.addEventListener(MouseEvent.MOUSE_UP,function(event){
  bar.stopDrag ();

  if (drag_flg) {
    var d = (bar.x - 117) / 200;
    if(duration)
    {
      netStream.seek(duration * d);
    }
  };

  drag_flg = false;
});

stage.addEventListener(Event.ENTER_FRAME,function(event){
  if (drag_flg)
  {
    return;
  }

  if (play_flg)
  {
    if(duration)
    {
      bar.x = 117 + netStream.time / duration * 200;
    }
  };
});

meter.addEventListener(Event.ENTER_FRAME,function(event){
  var scale = netStream.bytesLoaded / netStream.bytesTotal;
  if (scale > 1)
  {
    scale = 1;
  }
  if(scale < 0)
  {
    scale = 0;
  }
  meter.width = scale * 200;
});

var obj:Object = new Object();
obj.onMetaData = function(param:Object){
  duration = param.duration;
};
netStream.client = obj;

full_btn.addEventListener(MouseEvent.MOUSE_DOWN,MouseDownFunc);

function MouseDownFunc(e:MouseEvent):void{
  if (stage.displayState == StageDisplayState.NORMAL)
  {
    stage.displayState = StageDisplayState.FULL_SCREEN;
  }
  else
  {
    stage.displayState = StageDisplayState.NORMAL;
  }
}
