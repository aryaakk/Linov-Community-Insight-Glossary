<template>
<div class="footer">
  <div class="container">
    <div class="footer-widgets py-4 row">
      <div class="footer-area-1 col-md-3 col-sm-6">
        <section class="widget text-1 widget_text pt-3">
          <div class="widget-inner">
            <div><img src="/img/linov-light.png" alt="" width="126"></div>
            <div class="textwidget pt-3">
              <p>
                Pakuwon Tower, Jl. Casablanca No.Kav 88, RT.6/RW.14, Kb. Baru, Kec. Tebet, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12870
              </p>
            </div>
            <div>
              <h4>Follow Us</h4>
              <div class="soc-widget">
              <a target="_blank" href="https://www.facebook.com/linovhr">
                <i class="fab fa-facebook-square"></i>
              </a>
              <a target="_blank" href="https://www.linkedin.com/company/linovhr-indonesia/about/">
               <i class="fab fa-linkedin"></i>
              </a>
              <a target="_blank" href="https://www.instagram.com/linovhr/">
                <i class="fab fa-instagram"></i>
              </a>
            </div>
            </div>
          </div>
        </section>
      </div>
      <div class="footer-area-1 col-md-3 col-sm-6">
        <section class="widget text-1 widget_text pt-3">
          <div class="widget-inner">
            <h3 class="widget-title">About Us</h3>
            <div class="textwidget">
              <p style="text-align: justify;">Linov Community adalah wadah khusus yang diperuntukan untuk para praktisi HR dan juga mereka yang memiliki ketertarikan dengan dunia HR. Di dalam platform komunitas ini nantinya para anggota bisa mengajukan pertanyaan, berkonsultasi, mengikuti event, sampai juga menambah koneksi.</p>
            </div>
          </div>
        </section>
      </div>
      <div class="footer-area-3 col-md-3 col-sm-6">
        <section class="widget widget_nav_menu pt-3">
          <div class="widget-inner">
            <h3 class="widget-title">Product</h3>
            <ul>
              <li>
                <a href="https://www.linovhr.com/payroll-modul/" target="_blank">Payroll Services</a>
              </li>
              <li>
                <a href="https://www.linovhr.com/payroll-modul/" target="_blank">Software Payroll</a>
              </li>
              <li>
                <a href="https://www.linovhr.com/software-hris/" target="_blank">Software HRIS</a>
              </li>
              <li>
                <a href="https://rederp.co.id/" target="_blank">Software ERP</a>
              </li>
              <!-- <li>
                <a href="#">ESS (Employee Self Services)</a>
              </li> -->
            </ul>
          </div>
        </section>
      </div>
      <div class="footer-area-2 col-md-3 col-sm-6">
        <section class="widget widget_nav_menu pt-3">
          <div class="widget-inner">
            <h3 class="widget-title">Latest Insight</h3>
            <ul>
              <li v-for="(article, idx) in articles" :key="idx">
                <NuxtLink :to="`/insight/${article.slug_id}`">{{ article.title }}</NuxtLink>
              </li>
            </ul>
          </div>
        </section>
      </div>
    </div>
  </div>
  <div class="footer-btm-bar">
    <div class="container text-center">
          <p>
            <span class="footer_copy">&nbsp;© 2012 - {{ new Date().getFullYear() }} PT Linov Roket Prestasi. All Rights Reserved.</span>
          </p>
    </div>
  </div>
</div>
</template>
<style>
.footer {
    background: #292e31;
    color: #d2d2d2;
    padding: 20px 0 0;
    margin-top: 14px;
}
.footer-btm-bar {
    background: #23272a;
    padding: 30px 0 20px;
}

.footer ul{
  list-style-type: none; 
  padding: 0;
  margin: 0; 
}

.footer .widget.widget_nav_menu li a {
    border-bottom: 1px solid rgba(255,255,255,.05);
}
.widget.widget_nav_menu li a {
    padding: 6px 0;
    display: block;
    border-bottom: 1px solid #e6e6e6;
}
.footer p, .footer a {
    color: #d2d2d2;
}
.footer .widget-title {
    position: relative;
    font-size: 18px;
    font-weight: 400;
    margin-top: 0;
    padding-bottom: 10px;
    margin-bottom: 16px;
    border-bottom: 1px solid rgba(255,255,255,.1);
}
.footer .soc-widget a{
  font-size: 18px;
  padding-right: 8px;
}
</style>
<script>
export default {
  name: "section-footer",
  data() {
    return {
        articles: []
        }
  },
  async fetch(){
    await this.$getPopularArticle().then(response => {
        this.articles = response.data
    })
  }
}
</script>