import tornado
import tornado.web
import tornado.options
import json
import urllib
import base64
from aip import AipOcr

APP_ID = '11565668'
API_KEY = 'OITHqkONXs85sb5PozaznxfG'
SECRET_KEY = 'TdNc1vBV8M4lp1NouZmGezWfiXPOTZvA'

client = AipOcr(APP_ID, API_KEY, SECRET_KEY)


class ImageDetect(tornado.web.RequestHandler):
    def set_default_headers(self):
        # print("setting headers!!!")
        self.set_header("Access-Control-Allow-Origin", "*")
        self.set_header("Access-Control-Allow-Headers", "x-requested-with")
        self.set_header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS')

    def post(self):
        global client
        img = self.get_argument("image")
        img = img[img.find(",") + 1:]
        img_data = base64.b64decode(img)
        result = (client.basicGeneral(img_data))
        str = ""
        for i in result["words_result"]:
            str+=i["words"]
        print(json.dumps({"content":str}))
        self.write(json.dumps({"content":str}))
        pass


if __name__ == "__main__":
    app = tornado.web.Application([(r"/image", ImageDetect)])
    app.listen(8081)
    print("start")
    tornado.ioloop.IOLoop.current().start()
