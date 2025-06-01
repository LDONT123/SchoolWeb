<?php
require_once 'config.php';
$page_title_frontend = "云游校园";
include 'templates/header.php';
?>
    <!-- Main content specific to campus_tour.php starts here, header.php already started <main> -->
        <section class="page-title-section">
            <h1><?php echo htmlspecialchars($page_title_frontend); ?></h1>
            <p class="page-subtitle">欢迎来到昆明市盘龙区财大附中的互动式云游校园。在这里您可以通过图片和介绍探索我们的设施，感受校园氛围，发现我们学校的独特魅力。未来我们计划引入3D模型带来更沉浸式的体验。</p>
        </section>

        <section class="tour-main-container content-section-container">
            <div class="tour-gallery-area">
                <div class="slideshow-container">
                    <!-- Slideshow Images -->
                    <div class="tour-slide active-slide">
                        <img src="images/tour_main_entrance.jpg" alt="昆明市盘龙区财大附中 学校正门照片">
                        <div class="slide-caption">学校正门与访客中心</div>
                    </div>
                    <div class="tour-slide">
                        <img src="images/tour_library.jpg" alt="昆明市盘龙区财大附中 学校图书馆与媒体中心照片">
                        <div class="slide-caption">图书馆与媒体中心</div>
                    </div>
                    <div class="tour-slide">
                        <img src="images/tour_science_lab.jpg" alt="昆明市盘龙区财大附中 先进科学实验室照片">
                        <div class="slide-caption">先进科学实验室</div>
                    </div>
                    <div class="tour-slide">
                        <img src="images/tour_gymnasium.jpg" alt="昆明市盘龙区财大附中 体育中心与体育馆照片">
                        <div class="slide-caption">体育中心与体育馆</div>
                    </div>
                    <div class="tour-slide">
                        <img src="images/tour_auditorium.jpg" alt="昆明市盘龙区财大附中 表演艺术礼堂照片">
                        <div class="slide-caption">表演艺术礼堂</div>
                    </div>
                    <div class="tour-slide">
                        <img src="images/tour_cafeteria.jpg" alt="昆明市盘龙区财大附中 学校餐厅照片">
                        <div class="slide-caption">学生餐厅与公共区域</div>
                    </div>

                    <!-- Next/Previous Controls -->
                    <button class="prev-slide" aria-label="上一张幻灯片">&#10094;</button>
                    <button class="next-slide" aria-label="下一张幻灯片">&#10095;</button>
                </div>
            </div>

            <aside class="tour-navigation-info-area">
                <div class="tour-locations-list content-block">
                    <h3>探索我们的校园</h3>
                    <ul>
                        <li><a href="javascript:void(0);" class="tour-location-link active-location" data-location="main_entrance">学校正门与访客中心</a></li>
                        <li><a href="javascript:void(0);" class="tour-location-link" data-location="library">图书馆与媒体中心</a></li>
                        <li><a href="javascript:void(0);" class="tour-location-link" data-location="science_lab">先进科学实验室</a></li>
                        <li><a href="javascript:void(0);" class="tour-location-link" data-location="gymnasium">体育中心与体育馆</a></li>
                        <li><a href="javascript:void(0);" class="tour-location-link" data-location="auditorium">表演艺术礼堂</a></li>
                        <li><a href="javascript:void(0);" class="tour-location-link" data-location="cafeteria">学生餐厅与公共区域</a></li>
                    </ul>
                </div>

                <div class="tour-information-panels content-block">
                    <!-- Information Panels (Hidden by default, shown by JS) -->
                    <div id="info-main_entrance" class="info-panel active-info">
                        <h4>学校正门与访客中心</h4>
                        <p>这里是昆明市盘龙区财大附中的主要入口。我们热情的员工随时为访客和学生提供帮助。建筑风格融合了传统与现代元素。</p>
                    </div>
                    <div id="info-library" class="info-panel">
                        <h4>图书馆与媒体中心</h4>
                        <p>我们先进的图书馆提供海量书籍、数字资源、安静的学习区、协作工作空间以及最新技术，以支持学生的科研和学习。</p>
                    </div>
                    <div id="info-science_lab" class="info-panel">
                        <h4>先进科学实验室</h4>
                        <p>我们的科学实验室配备了现代化的仪器和安全设施，为生物、化学、物理和环境科学等学科的实验提供了引人入胜的环境。</p>
                    </div>
                    <div id="info-gymnasium" class="info-panel">
                        <h4>体育中心与体育馆</h4>
                        <p>这里是[昆明市盘龙区财大附中吉祥物名称]队的主场！我们的体育中心拥有标准篮球场、排球设施、健身中心和更衣室，支持广泛的体育运动和体育教学项目。</p>
                    </div>
                    <div id="info-auditorium" class="info-panel">
                        <h4>表演艺术礼堂</h4>
                        <p>我们的礼堂可容纳[数字]名观众，是举办学生戏剧、音乐表演、嘉宾讲座和学校集会的场所。礼堂配备专业的灯光和音响系统。</p>
                    </div>
                    <div id="info-cafeteria" class="info-panel">
                        <h4>学生餐厅与公共区域</h4>
                        <p>一个明亮宽敞的区域，供学生用餐、社交和协作。我们每天提供各种健康美味的食物选择。</p>
                    </div>
                </div>
            </aside>
        </section>

        <article class="content-section-container">
            <section class="content-block text-center">
                <h2>有兴趣了解更多吗？</h2>
                <p>虽然我们的云游校园让您得以一窥校园风貌，但亲身体验始终无可替代。我们诚邀未来的学生和家庭更多地了解我们的招生流程并安排参观。</p>
                <a href="admissions.html" class="cta-button primary-cta">招生信息</a>
                <a href="contact.html#schedule-visit" class="cta-button secondary-cta">安排实地参观</a>
            </section>
        </article>
    </main>

    <footer>
        <!-- Footer - Same as index.html -->
        <div class="footer-content">
            <div class="contact-info">
                <p>昆明市盘龙区财大附中</p>
                <p>123 School Lane, City, State, ZIP</p>
                <p>电话：(123) 456-7890</p>
                <p>邮箱：info@[schoolname].edu</p>
            </div>
            <div class="footer-links">
                <a href="#">隐私政策</a>
                <a href="#">无障碍声明</a>
                <a href="#">网站问题反馈</a>
                <a href="contact.html">联系我们</a>
            </div>
            <div class="social-media-links">
                <a href="#" aria-label="Facebook">FB</a>
                <a href="#" aria-label="Twitter">TW</a>
                <a href="#" aria-label="Instagram">IG</a>
            </div>
        </div>
        <p class="copyright">&copy; <span id="current-year"></span> 昆明市盘龙区财大附中. 版权所有。</p>
<?php
include 'templates/footer.php';
?>
