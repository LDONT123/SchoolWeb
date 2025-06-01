<?php
require_once 'config.php';
$page_title_frontend = "联系我们";
include 'templates/header.php';
?>
    <!-- Main content specific to contact.php starts here, header.php already started <main> -->
        <section class="page-title-section">
            <h1><?php echo htmlspecialchars($page_title_frontend); ?></h1>
            <p class="page-subtitle">我们随时为您提供帮助。如有任何疑问，或希望安排参观校园，请与我们联系。</p>
        </section>

        <article class="content-section-container contact-flex-container">
            <section class="content-block contact-info-block">
                <h2>联系方式</h2>
                <div class="contact-details">
                    <p><strong><span class="icon-placeholder">📍</span>地址：</strong><br>
                        昆明市盘龙区财大附中<br>
                        创新大道123号<br>
                        学习顿市，州名 54321<br>
                        美国
                    </p>
                    <p><strong><span class="icon-placeholder">📞</span>总机电话：</strong> <a href="tel:+1-123-456-7890">(123) 456-7890</a></p>
                    <p><strong><span class="icon-placeholder">📠</span>传真：</strong> (123) 456-7899</p>
                    <p><strong><span class="icon-placeholder">✉️</span>通用邮箱：</strong> <a href="mailto:info@cdfz.edu">info@cdfz.edu</a></p>
                    <p><strong><span class="icon-placeholder">⏰</span>办公时间：</strong><br>
                        周一至周五：上午7:30 - 下午4:30 (学年期间)<br>
                        周一至周四：上午9:00 - 下午3:00 (暑期)
                    </p>
                </div>

                <h3>各部门联系方式：</h3>
                <ul class="contact-list">
                    <li><strong>招生办公室：</strong> <a href="mailto:admissions@[schoolname].edu">admissions@[schoolname].edu</a> | <a href="tel:+1-123-456-7891">(123) 456-7891</a></li>
                    <li><strong>考勤热线：</strong> <a href="tel:+1-123-456-7892">(123) 456-7892</a></li>
                    <li><strong>辅导咨询室：</strong> <a href="mailto:counseling@[schoolname].edu">counseling@[schoolname].edu</a></li>
                    <li><strong>体育部门：</strong> <a href="mailto:athletics@[schoolname].edu">athletics@[schoolname].edu</a></li>
                </ul>
            </section>

            <section class="form-section content-block contact-form-block">
                <h2>给我们留言</h2>
                <form id="contact-form" action="#" method="POST">
                    <div class="form-group">
                        <label for="full-name">全名：</label>
                        <input type="text" id="full-name" name="fullName" required placeholder="例如：张三">
                    </div>
                    <div class="form-group">
                        <label for="email">电子邮箱：</label>
                        <input type="email" id="email" name="email" required placeholder="例如：zhang.san@example.com">
                    </div>
                    <div class="form-group">
                        <label for="phone">电话号码 (可选)：</label>
                        <input type="tel" id="phone" name="phone" placeholder="例如：(123) 456-7890">
                    </div>
                    <div class="form-group">
                        <label for="topic">我感兴趣的主题：</label>
                        <select id="topic" name="topic" required>
                            <option value="" disabled selected>请选择一个主题...</option>
                            <option value="general_inquiry">一般咨询</option>
                            <option value="admissions">招生信息</option>
                            <option value="academics">学术问题</option>
                            <option value="campus_visit">安排校园参观</option>
                            <option value="support">技术支持</option>
                            <option value="other">其他</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">留言内容：</label>
                        <textarea id="message" name="message" rows="6" required placeholder="请在此输入您的留言..."></textarea>
                    </div>
                    <button type="submit" class="cta-button primary-cta">发送留言</button>
                </form>
            </section>
        </article>

        <section class="content-section-container" id="map-directions-container">
             <div class="content-block">
                <h2>在地图上找到我们</h2>
                <!-- Placeholder for an embedded map (e.g., Google Maps) -->
                <div id="map-placeholder" style="width:100%; height:400px; background-color:#EAEAEA; text-align:center; display:flex; align-items:center; justify-content:center; border-radius: var(--border-radius); margin-bottom: 15px;">
                    <p style="color: #757575;"><em>[ 交互式地图将在此处嵌入 ]</em></p>
                </div>
                <div class="text-center">
                    <a href="https://maps.google.com/?q=123+Innovation+Drive,+Learnington,+ST+54321" target="_blank" class="cta-button secondary-cta">在谷歌地图上获取路线</a>
                </div>
            </div>
        </section>

        <article class="content-section-container">
            <section class="content-block" id="schedule-visit-info">
                <h2 id="schedule-visit">计划来访？</h2>
                <p>我们欢迎未来的学生及其家人参观我们的校园！如需安排导览或与我们的招生团队会面，请直接致电招生办公室 <a href="tel:+1-123-456-7891">(123) 456-7891</a> 或发送电子邮件至 <a href="mailto:admissions@[schoolname].edu">admissions@[schoolname].edu</a>。您也可以使用上方的联系表表明您的来访意向。</p>
                <p>其他类型的访问，请联系总办公室进行安排。</p>
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
