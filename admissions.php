<?php
require_once 'config.php';
$page_title_frontend = "招生信息";
include 'templates/header.php';
?>
    <!-- Main content specific to admissions.php starts here, header.php already started <main> -->
        <section class="page-title-section">
            <h1><?php echo htmlspecialchars($page_title_frontend); ?></h1>
            <p class="page-subtitle">加入我们充满活力的学习社区。查找申请和注册所需的所有信息。</p>
        </section>

        <article class="content-section-container">
            <section class="content-block" id="welcome-admissions">
                <h2>欢迎未来的同学们！</h2>
                <p>我们很高兴您考虑加入昆明市盘龙区财大附中！我们的招生流程旨在识别渴望学习、为我们学校社区做出贡献并勇于接受新挑战的学生。我们鼓励您浏览我们的网站，参加我们的云游校园，如有任何问题，请随时与我们联系。</p>
            </section>

            <section class="content-block" id="how-to-apply">
                <h2>申请流程与时间表</h2>
                <p>申请昆明市盘龙区财大附中涉及几个关键步骤。请仔细阅读时间表，并确保在截止日期前提交所有材料。</p>
                <ol class="process-list">
                    <li><strong>探索与咨询：</strong> 参加开放日活动（如有，可选择线上或线下），参与我们的云游校园，并查阅我们的学术项目。如需更多信息，请随时索取。</li>
                    <li><strong>在线申请：</strong> 在[申请截止日期，例如1月15日]前，通过我们的安全门户网站完成并提交在线申请表。需缴纳不可退还的申请费[费用金额]美元。</li>
                    <li><strong>提交文件：</strong> 提供您当前学校的官方成绩单、[数量]封推荐信（例如，一封来自数学/科学教师，一封来自英语/人文学科教师），以及标准化考试成绩（如适用，例如SSAT、ISEE或州级考试成绩）。</li>
                    <li><strong>学生面试/评估（如适用）：</strong> 部分申请者可能会被邀请参加简短的面试或完成一项小型评估。此步骤的通知将在[日期]前发送。</li>
                    <li><strong>录取决定：</strong> 录取通知书将在[录取通知日期，例如3月1日]邮寄并以电子邮件形式发送给所有申请者。</li>
                    <li><strong>注册入学：</strong> 被录取的学生必须在[注册截止日期，例如3月15日]前完成注册文件并缴纳押金，以确保入学名额。</li>
                </ol>
                <div class="text-center section-cta">
                    <a class="cta-button primary-cta">开始在线申请</a>
                </div>
            </section>

            <section class="content-block" id="admission-criteria">
                <h2>录取标准</h2>
                <p>我们的招生委员会考虑多种因素，以确保招收全面发展、能力优秀的新生。这些因素包括：</p>
                <ul class="feature-list">
                    <li>学业成绩和潜力（通过成绩单体现）。</li>
                    <li>教师和辅导员的推荐信。</li>
                    <li>标准化考试成绩（在适用和考虑的情况下）。</li>
                    <li>课外活动参与情况、才能和兴趣。</li>
                    <li>个人陈述或学生问卷（在线申请的一部分）。</li>
                    <li>对学习和社区价值观的承诺。</li>
                </ul>
            </section>
        </article>

        <section class="card-based-section" id="visit-us">
            <h2>探索昆明市盘龙区财大附中</h2>
            <div class="card-container">
                <article class="card">
                    <div class="card-icon"><span>🗺️</span></div>
                    <h3>云游校园</h3>
                    <p>随时随地探索我们先进的设施、教室和校园环境。</p>
                    <a href="campus_tour.html" class="card-link">开始云游</a>
                </article>
                <article class="card">
                    <div class="card-icon"><span>🗓️</span></div>
                    <h3>开放日活动</h3>
                    <p>参加我们即将举行的开放日活动，与教职员工和在校学生见面，更多地了解昆明市盘龙区财大附中的生活。</p>
                    <a class="card-link">查看开放日日期</a>
                </article>
                <article class="card">
                    <div class="card-icon"><span>❓</span></div>
                    <h3>招生常见问题解答</h3>
                    <p>查找关于我们招生流程、学费和助学金的常见问题解答。</p>
                    <a href="#faq-section" class="card-link">阅读常见问题</a>
                </article>
            </div>
        </section>

        <article class="content-section-container">
            <section class="content-block" id="tuition-financial-aid">
                <h2>学费与助学金</h2>
                <p>关于[学年]学年的学费信息可在此处找到[此处或可下载的PDF文件]。我们致力于让昆明市盘龙区财大附中的教育惠及更多家庭，并为符合条件的家庭提供基于需求的助学金项目。</p>
                <p>助学金申请截止日期为[助学金申请截止日期]，与入学申请同步处理。欲了解更多详情，请访问我们的助学金页面或联系招生办公室。</p>
                <div class="text-center section-cta">
                    <a href="#" class="cta-button secondary-cta">了解助学金信息</a>
                </div>
            </section>

            <section class="content-block" id="faq-section">
                <h2>常见问题解答</h2>
                <details class="faq-item">
                    <summary>下一学年（[Next Academic Year]）的主要入学申请截止日期是什么时候？</summary>
                    <p class="faq-answer">主要申请截止日期是[申请截止日期]。助学金申请截止日期是[助学金申请截止日期]。有关所有重要日期的完整列表，请参阅上方的招生时间表。</p>
                </details>
                <details class="faq-item">
                    <summary>入学需要参加入学考试吗？</summary>
                    <p class="faq-answer">对于申请[年级]的学生，我们[需要/不需要]特定的入学考试。我们[接受/不接受]来自[如适用，请提及具体考试，例如SSAT、ISEE]的成绩。详情请参阅“提交文件”步骤。</p>
                </details>
                <details class="faq-item">
                    <summary>学校的师生比例是多少？</summary>
                    <p class="faq-answer">我们的平均师生比例约为[比例，例如12:1]，从而确保课堂上的个性化关注和有意义的互动。</p>
                </details>
                 <details class="faq-item">
                    <summary>学校提供奖学金吗？</summary>
                    <p class="faq-answer">虽然我们的主要经济援助是基于需求的助学金，但我们也为在[例如学术、艺术、领导力]方面表现出卓越才能的学生提供数量有限的优秀奖学金。所有申请者都将自动被考虑。</p>
                </details>
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
