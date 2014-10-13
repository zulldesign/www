package
{
	import flash.display.Sprite;
	import flash.events.MouseEvent;
	import flash.external.ExternalInterface;
	import flash.text.TextField;
	
	public class FacebookDemo extends Sprite
	{
		private var txt:TextField;
		public function FacebookDemo()
		{	
			init();
		}
		
		private function init():void{
			ExternalInterface.addCallback("myFlashcall",myFlashcall);
			stage.addEventListener(MouseEvent.CLICK, onClick);
		}
		
		private function myFlashcall(str:String):void
		{
			trace("myFlashcall: "+str);
		}
		
		protected function onClick(event:MouseEvent):void
		{
			if(ExternalInterface.available){
				trace("onClick");
				ExternalInterface.call("myFBcall");
			}
		}
	}
}