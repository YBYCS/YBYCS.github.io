//樱花 sakura

//图片
var img = new Image();
img.src = "/img/sakura.png";

// 页面上樱花的数量 
var sakuraNum = 21;
// 樱花可以超出界面多少次(为0时不再有樱花) -1为无限制(就是一直重复樱花飘落)
var limitTimes = -1;

// 定义限制数组 用来批量管理每一朵樱花是否还能重新飘落
var limitArray = new Array(sakuraNum);
for(var index = 0;index < sakuraNum;index++){
    limitArray[index] = limitTimes;
}

// 定义樱花对象, idx 超过多少次
function Sakura(x, y, s, r, fn, idx) {
	this.x = x;
	this.y = y;
	this.s = s;//显示大小(倍率)
	this.r = r;//旋转
	this.fn = fn;//用来计算速度的函数
	this.idx = idx;
}

// 绘制樱花
Sakura.prototype.draw = function(cxt) {
	//这里比较难以理解 save()是储存canvas的状态 这里储存的是一开始的默认状态 
	cxt.save();
	cxt.translate(this.x, this.y);//移动画布左上角
	cxt.rotate(this.r);//旋转绘图
	cxt.drawImage(img, 0, 0, 40 * this.s, 40 * this.s);//绘画
	//经过上面的 translate rotate画布会改变状态(最上角平移了 本身旋转了) 所以要用restore恢复成save储存的状态 也就是默认状态
	//相当于 我画樱花在同一个地方 但是画布挪动了 也就是笔的位置不懂 我调整画布来画画
	cxt.restore();
}

// 修改樱花位置,模拟飘落.
Sakura.prototype.update = function() {
	//更新下一帧的状态(位置,,旋转)
	this.x = this.fn.x(this.x, this.y);
	this.y = this.fn.y(this.y, this.y);
	this.r = this.fn.r(this.r);

	// 如果樱花越界, 重新调整位置回到屏幕来
	if(this.x > window.innerWidth || this.x < 0 ||
		this.y > window.innerHeight || this.y < 0) {

		// 如果樱花不做限制 就不断的回到屏幕
		if (limitArray[this.idx] == -1) {
			//获取随机值来初始化 getRandom是用来初始化的一个函数
			this.r = getRandom('fnr');
			if(Math.random() > 0.4) {
				this.x = getRandom('x');
				this.y = 0;
				this.s = getRandom('s');
				this.r = getRandom('r');
			} else {
				this.x = window.innerWidth;
				this.y = getRandom('y');
				this.s = getRandom('s');
				this.r = getRandom('r');
			}
		}
		// 樱花有限制 着记次数
		else {
			if (limitArray[this.idx] > 0) {
				this.r = getRandom('fnr');
				if(Math.random() > 0.4) {
					this.x = getRandom('x');
					this.y = 0;
					this.s = getRandom('s');
					this.r = getRandom('r');
				} else {
					this.x = window.innerWidth;
					this.y = getRandom('y');
					this.s = getRandom('s');
					this.r = getRandom('r');
				}
				// 该越界的樱花限制数减一
				limitArray[this.idx]--;
			}
		}
	}
}

//樱花列表
SakuraList = function() {
	this.list = [];
}
SakuraList.prototype.push = function(sakura) {
	this.list.push(sakura);
}

// list update 方法 用来遍历更新樱花的下一帧状态
SakuraList.prototype.update = function() {
	for(var i = 0, len = this.list.length; i < len; i++) {
		this.list[i].update();
	}
}

// list draw 方法
SakuraList.prototype.draw = function(cxt) {
	for(var i = 0, len = this.list.length; i < len; i++) {
		this.list[i].draw(cxt);
	}
}
SakuraList.prototype.get = function(i) {
	return this.list[i];
}
SakuraList.prototype.size = function() {
	return this.list.length;
}

// 位置随机策略 根据传入的值不同 返回不同结果
function getRandom(option) {
	var ret, random;
	switch(option) {
		case 'x':
			ret = Math.random() * window.innerWidth;
			break;
		case 'y':
			ret = Math.random() * window.innerHeight;
			break;
		case 's':
			ret = Math.random();
			break;
		case 'r':
			ret = Math.random() * 6;
			break;
			//fnx fny是控制两个方向的速度的 这里是返回生成对应随机数的函数
		case 'fnx':
			random = -0.5 + Math.random() * 1;
			ret = function(x, y) {
				return x + 0.5 * random - 1;
			};
			break;
		case 'fny':
			random = 1.5 + Math.random() * 0.7
			ret = function(x, y) {
				return y + random;
			};
			break;
		case 'fnr':
			random = Math.random() * 0.03;
			ret = function(r) {
				return r + random;
			};
			break;
	}
	return ret;
}

// 加载入口
function startSakura() {

  
	requestAnimationFrame = window.requestAnimationFrame ||
		window.mozRequestAnimationFrame ||
		window.webkitRequestAnimationFrame ||
		window.msRequestAnimationFrame ||
		window.oRequestAnimationFrame;
		//画布 用来绘制
	var canvas = document.createElement('canvas'),
		cxt;

	canvas.height = window.innerHeight;
	canvas.width = window.innerWidth;
	canvas.setAttribute('style', 'position: fixed;left: 0;top: 0;pointer-events: none;');
	canvas.setAttribute('id', 'canvas_sakura');
	document.getElementsByTagName('body')[0].appendChild(canvas);
	cxt = canvas.getContext('2d');
	var sakuraList = new SakuraList();
	// sakuraNum 樱花个数 
	for(var i = 0; i < sakuraNum; i++) {
		var sakura, randomX, randomY, randomS, randomR, randomFnx, randomFny;
		randomX = getRandom('x');
		randomY = getRandom('y');
		randomR = getRandom('r');
		randomS = getRandom('s');
		randomFnx = getRandom('fnx');
		randomFny = getRandom('fny');
		randomFnR = getRandom('fnr');
		//fn枚举型 主要是用来向getRandom函数传参
		sakura = new Sakura(randomX, randomY, randomS, randomR, {
			x: randomFnx,
			y: randomFny,
			r: randomFnR
		}, i);
		sakura.draw(cxt);
		sakuraList.push(sakura);
	}

	stop = requestAnimationFrame(function() {
		//擦除画布重新画下一帧
		cxt.clearRect(0, 0, canvas.width, canvas.height);
		// 修改樱花位置逻辑
		sakuraList.update();
		// 画出修改后的樱花
		sakuraList.draw(cxt);
		// 递归 修改位置, 画出修改后的樱花
		stop = requestAnimationFrame(arguments.callee);
	})
}
// 用来反正改变大小时产生空指针
window.onresize = function() {
	var canvasSnow = document.getElementById('canvas_snow');
	// canvasSnow 在改变浏览器大小的时候会为null (修改空指针异常), 不过在改变大小时体验稍差
	if (canvasSnow) {
		canvasSnow.width = window.innerWidth;
		canvasSnow.height = window.innerHeight;
	}
}
//当页面载入执行
img.onload = function() {
	startSakura();
}


