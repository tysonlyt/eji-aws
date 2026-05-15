import RightClick from "../../src/Components/RightClickProtector/js/right-click";
import TextCopy from "../../src/Components/TextCopyProtector/js/text-copy";
import Images from "../../src/Components/ImagesProtector/js/images";
import Iframe from "../../src/Components/IframeProtector/js/iframe";
import Videos from "../../src/Components/VideoProtector/js/video";
import Audios from "../../src/Components/AudioProtector/js/audio";
import DeveloperTools from "../../src/Components/DeveloperToolsProtector/js/developer-tools";
import ViewSource from "../../src/Components/ViewSourceProtector/js/view-source";
import Print from "../../src/Components/PrintProtector/js/print";
import Email from "../../src/Components/EmailProtector/js/email";
import Phone from "../../src/Components/PhoneProtector/js/phone";
import ExtensionsManager, { AlertExtensionsHandler, FilterAndCondition } from "./extensions-manager";

class App {

	constructor() {

		new RightClick();
		new TextCopy();
		new Images();
		new Iframe();
		new Videos();
		new Audios();
		new DeveloperTools();
		new ViewSource();
		new Print();
		new Email();
		new Phone();
	}
}

new App();


export default {
	ExtensionsManager,
	FilterAndCondition,
	AlertExtensionsHandler
};