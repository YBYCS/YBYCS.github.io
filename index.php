<!DOCTYPE html>
<html lang="zh" style="height: 100%;">

<head>
    <meta charset="utf-8" />
    <meta name="description" content="ybycs的博客">
    <title>妖白与茶色|YBYCS</title>

    <link rel="stylesheet" type="text/css" href="/css/baselayout.css">
    <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css">
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.3/jquery.js"></script>
    <script src="/js/sakura.js"></script>


    <script>
        // $(document).ready(function () {
        //     var i = -1;
        //     addTips = function (e) {
        //         var a = new Array("富强", "民主", "文明", "和谐", "自由", "平等", "公正", "法治", "爱国", "敬业", "诚信",
        //             "友善");
        //         var s = $("<span/>").text(a[i]);
        //         i = (i + 1) % a.length;
        //         var x = e.pageX,
        //             y = e.pageY;


        //         s.css({
        //             "z-index": 9999999999999,
        //             "top": y - 20,
        //             "left": x,
        //             "position": "absolute",
        //             "font-weight": "bold",
        //             "color": "#ff6651"
        //         });
        //         $("body").append(s);

        //         s.animate({
        //             "top": y - 180,
        //             "opacity": 0
        //         }, 1000, function () {
        //             s.remove();
        //         });

        //     }
        //     $("body").click(addTips);
        //     $("body").bind("contextmenu", addTips);
        // });

        function search() {
            var value = document.getElementById("searchText");
            window.open("https://www.bing.com/search?q=" + value.value);
        }
    </script>




</head>



<body lang="zh" style=" height:100%;">

    <div id="page-container">
        <div id="page-header">
            <div id="header-container">
                <a href="index.html" rel="noopener">
                    <span>首页</span>
                </a>
                <a href="friendlink.html" rel="noopener">
                    <span>友链</span>
                </a>
                <a href="about.html" rel="noopener">
                    <span>关于</span>
                </a>
                <a href="massage.html" rel="noopener">
                    <span>留言</span>
                </a>
                <a rel="noopener" href="https://github.com/YBYCS/YBYCS.github.io" style="float:right;"
                    title="Download on GitHub">
                    <i class="fab fa-github "></i>
                </a>
                <div id="search">
                    <form>
                        <input id="searchText" type="text" name="search">
                    </form>
                    <a onclick="search()" style="float:right;">
                        <i class="fas fa-search"></i>
                    </a>
                </div>

            </div>
        </div>

        <div id="page-body">
            <div id="content-container">
                <div class="toolbar"></div>
                <div id="page-content">
                    <!-- 左界面 -->
                    <div id="page-left-container">
                        <div id="showmyself">

                            <img src="img/Himeno Sena.jpg" width="75%" />
                            <h1 style="margin: 1px;">YBYCS</h1>
                            <h3 style="margin: 1px;">知其不可而为之</h3>

                            <a style="color: black;" target="_blank" rel="noopener" title="Email"
                                href="mailto:1738146845@qq.com">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 右界面 -->
                    <div id="page-right-container">
                        <div id="page-right">

                            <!-- 页码旋转 -->
                            <div id="pageSelectContainer" class="pageSelectContainer">
                                <div id="pageCardContainer" class="pageCardContainer">
                                </div>

                            </div>

                        </div>
                    </div>

                    <!--取消浮动并且占据一个位置-->
                    <div style="clear: both;" border:true></div>

                </div>

            </div>

        </div>
        <div id="page-foot">
            <div id="icp-container"">
                <a href=" http://beian.miit.gov.cn/" target="_blank">粤ICP备2023007063号</a>
            </div>
        </div>
    </div>

    <script>
        $.ajax({
            url: "data",
            type: "GET",
            dataType: "json",
            success: function (data) {
                dealData(data)
            }
        })
        var dealData = function (data) {
            const container = document.getElementById("page-right")
            const searchParams = new URLSearchParams(window.location.search);
            var page = searchParams.get("page");
            if (page == null) {
                //默认从第一页开始
                page = 1;
            }
            var maxPage = 7;
            //动态添加页面
            for (var i = (page - 1) * maxPage; i < page * maxPage && i < data.articles.length; i++) {
                var str = " <div class=\"article-lead canClick\" onclick=\"window.open('Article.html?www=" + data
                    .articles[i].www + "', '_blank');\"><h1 class=\"article-title\">" + data.articles[i].title +
                    "</h1><p>" + data.articles[i].brief + "</p></div>";
                container.innerHTML += str;
            }

            const pageContainer = document.getElementById("pageCardContainer")
            //动态添加页数
            for (var i = 0; i <= data.articles.length / (maxPage + 1); i++) {
                var str = "<div class=\"pageCard canClick selected-page\" onclick=\"window.open('/index.html?page="+
                    (i + 1) + "','window.location.href');\"><a href=\"/index.html?page=" + (i + 1) +
                    "\" rel=\"noopener\"style=\"text-decoration:none;color:#000\">" + (i + 1) + "</a></div>";
                pageContainer.innerHTML += str;
            }

            let element = document.getElementById("pageSelectContainer");
            element.parentNode.appendChild(element);
        }
    </script>
</body>

</html>