<?php
require_once 'config.php';
$page_title_frontend = "关于我们";
include 'templates/header.php';
?>
    <!-- Main content specific to about.php starts here, header.php already started <main> -->
        <section class="page-title-section">
            <h1><?php echo htmlspecialchars($page_title_frontend); ?></h1>
            <p class="page-subtitle">了解更多关于我们的历史、价值观以及使我们学校成为卓越中心的敬业团队。</p>
        </section>

        <article class="content-section-container">
            <section class="content-block" id="mission-vision">
                <h2>我们的使命与愿景</h2>
                <div class="mission-vision-flex">
                    <div class="mission-item">
                        <h3>我们的使命</h3>
                        <p>营造一个充满活力和包容性的学习环境，挑战并激励学生追求学术卓越，培养批判性思维，并塑造健全的人格。我们致力于通过提供多样化的机会和培养尊重、责任和终身学习的文化，为所有学生在大学、职业和生活中取得成功做好准备。</p>
                    </div>
                    <div class="vision-item">
                        <h3>我们的愿景</h3>
                        <p>昆明市盘龙区财大附中将成为一所领先的教育机构，以其创新的教育项目、对学生福祉的承诺以及在赋能学生成为富有同情心、积极参与的公民和快速发展的全球社会领导者方面的作用而获得认可。我们展望未来，每一位学生都能带着追求梦想的技能和信心毕业。</p>
                    </div>
                </div>
            </section>

            <section class="content-block" id="history">
                <h2>我们的历史</h2>
                <p>成立于[年份]年，昆明市盘龙区财大附中拥有悠久的学术成就和社区参与传统。从最初只有[数字]名学生的简陋开端，我们已发展成为一所服务于多元化学生群体的综合性高中。几十年来，我们不断调整我们的课程和设施，以满足学生和社区不断变化的需求。</p>
                <p>重要的里程碑包括[提及一个关键里程碑，例如“1998年我们最先进的科学翼楼的建设”或“2015年我们1:1技术计划的启动”]。我们为我们的传统感到自豪，并对我们共同建设的未来充满期待。</p>
            </section>

            <section class="content-block" id="core-values">
                <h2>我们的核心价值观</h2>
                <ul>
                    <li><strong>卓越：</strong> 在所有努力中追求最高标准。</li>
                    <li><strong>诚信：</strong> 坚持诚实、道德和相互尊重。</li>
                    <li><strong>社区：</strong> 为所有人营造一个支持性和包容性的环境。</li>
                    <li><strong>创新：</strong> 拥抱创造性和前瞻性的学习方法。</li>
                    <li><strong>坚韧：</strong> 鼓励在面对挑战时坚持不懈和适应能力。</li>
                </ul>
            </section>
        </article>

        <section class="card-based-section" id="leadership-staff">
            <h2>认识我们的领导团队</h2>
            <div class="card-container">
                <article class="card profile-card">
                    <img src="images/placeholder_principal.jpg" alt="[校长姓名]校长照片占位图">
                    <div class="profile-card-content">
                        <h3>[校长姓名]</h3>
                        <p class="profile-title">校长</p>
                        <p>拥有[数字]年的教育经验，[他/她/他们]致力于营造学术卓越和学生成功的环境。</p>
                        <a class="card-link">阅读完整简介</a>
                    </div>
                </article>
                <article class="card profile-card">
                    <img src="images/placeholder_vp.jpg" alt="[副校长姓名]副校长照片占位图">
                    <div class="profile-card-content">
                        <h3>[副校长姓名]</h3>
                        <p class="profile-title">副校长</p>
                        <p>[他/她/他们]专注于学生事务和课程开发，确保为所有人提供支持性和富有挑战性的学习体验。</p>
                        <a class="card-link">阅读完整简介</a>
                    </div>
                </article>
                <article class="card profile-card">
                    <img src="images/placeholder_counselor.jpg" alt="[辅导主任姓名]辅导主任照片占位图">
                    <div class="profile-card-content">
                        <h3>[辅导主任姓名]</h3>
                        <p class="profile-title">辅导主任</p>
                        <p>领导我们的辅导部门，[他/她/他们]致力于支持学生的学术、社交和情感福祉。</p>
                        <a class="card-link">阅读完整简介</a>
                    </div>
                </article>
            </div>
            <div class="text-center section-cta">
                <a class="cta-button secondary-cta">查看完整教职工名录</a>
            </div>
        </section>

    </main>

    <footer>
        <!-- Footer - Same as index.html -->
        <div class="footer-content">
            <div class="contact-info">
                <p>昆明市盘龙区财大附中</p>
                <p>123 School Lane, City, State, ZIP</p>
                <p>电话：(123) 456-7890</p>
                <p>邮箱：info@cdfz.edu</p>
            </div>
            <div class="footer-links">
                <a class="footer-link-item">隐私政策</a>
                <a class="footer-link-item">无障碍声明</a>
                <a class="footer-link-item">网站问题反馈</a>
                <a href="contact.html">联系我们</a>
            </div>
            <div class="social-media-links">
                <a class="social-link-item" aria-label="Facebook">FB</a>
                <a class="social-link-item" aria-label="Twitter">TW</a>
                <a class="social-link-item" aria-label="Instagram">IG</a>
            </div>
        </div>
        <p class="copyright">&copy; <span id="current-year"></span> 昆明市盘龙区财大附中. 版权所有。</p>
<?php
include 'templates/footer.php';
?>
