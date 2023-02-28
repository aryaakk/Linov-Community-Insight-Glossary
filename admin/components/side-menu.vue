<template>
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme h-100">
          <div class="app-brand demo">
            <a href="index.html" class="app-brand-link">
              <span class="app-brand-logo demo">
                <svg
                  width="25"
                  viewBox="0 0 25 42"
                  version="1.1"
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink"
                >
                  <defs>
                    <path
                      d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                      id="path-1"
                    ></path>
                    <path
                      d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                      id="path-3"
                    ></path>
                    <path
                      d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                      id="path-4"
                    ></path>
                    <path
                      d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                      id="path-5"
                    ></path>
                  </defs>
                  <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                      <g id="Icon" transform="translate(27.000000, 15.000000)">
                        <g id="Mask" transform="translate(0.000000, 8.000000)">
                          <mask id="mask-2" fill="white">
                            <use xlink:href="#path-1"></use>
                          </mask>
                          <use fill="#696cff" xlink:href="#path-1"></use>
                          <g id="Path-3" mask="url(#mask-2)">
                            <use fill="#696cff" xlink:href="#path-3"></use>
                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                          </g>
                          <g id="Path-4" mask="url(#mask-2)">
                            <use fill="#696cff" xlink:href="#path-4"></use>
                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                          </g>
                        </g>
                        <g
                          id="Triangle"
                          transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) "
                        >
                          <use fill="#696cff" xlink:href="#path-5"></use>
                          <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                        </g>
                      </g>
                    </g>
                  </g>
                </svg>
              </span>
              <span class="app-brand-text demo menu-text fw-bolder ms-2">Linov Community</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item" :class="$route.path=='/' ? 'active' : ''">
              <nuxt-link to="/" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
              </nuxt-link>
            </li>

            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Layouts">Master Data</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item" :class="$route.path=='/industries' ? 'active' : ''">
                  <nuxt-link to="/industries" class="menu-link">
                    Industri
                  </nuxt-link>
                </li>
                <li class="menu-item" :class="$route.path=='/positions' ? 'active' : ''">
                  <nuxt-link to="/positions" class="menu-link">
                    Posisi
                  </nuxt-link>
                </li>
                <li class="menu-item" :class="$route.path=='/university' ? 'active' : ''">
                  <nuxt-link to="/university" class="menu-link">
                    University
                  </nuxt-link>
                </li>
                <li class="menu-item" :class="$route.path=='/degrees' ? 'active' : ''">
                  <nuxt-link to="/degrees" class="menu-link">
                    Degree
                  </nuxt-link>
                </li>
                <li class="menu-item" :class="$route.path=='/majors' ? 'active' : ''">
                  <nuxt-link to="/majors" class="menu-link">
                    Jurusan
                  </nuxt-link>
                </li>
                <li class="menu-item" :class="$route.path=='/banks' ? 'active' : ''">
                  <nuxt-link to="/banks" class="menu-link">
                    Banks
                  </nuxt-link>
                </li>
                <li class="menu-item" :class="$route.path=='/category-consultant' ? 'active' : ''">
                  <nuxt-link to="/category-consultant" class="menu-link">
                    Kategori Konsultan
                  </nuxt-link>
                </li>
                <li class="menu-item" :class="$route.path=='/social-media' ? 'active' : ''">
                  <nuxt-link to="/social-media" class="menu-link">
                    Sosial Media
                  </nuxt-link>
                </li>
                <li class="menu-item" :class="$route.path=='/skills' ? 'active' : ''">
                  <nuxt-link to="/skills" class="menu-link">
                    Skills
                  </nuxt-link>
                </li>
              </ul>
            </li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bx-building-house'></i>
                <div data-i18n="Form Layouts">Master Region</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item" :class="$route.path=='/state' ? 'active' : ''">
                  <nuxt-link to="/state" class="menu-link">
                    State
                  </nuxt-link>
                </li>
                <li class="menu-item" :class="$route.path=='/city' ? 'active' : ''">
                  <nuxt-link to="/city" class="menu-link">
                    City
                  </nuxt-link>
                </li>
              </ul>
            </li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bx-calendar-event'></i>
                <div data-i18n="Form Layouts">Training & Events</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item" :class="$route.path=='/trainers' ? 'active' : ''">
                  <nuxt-link to="/trainers" class="menu-link">
                    Trainers
                  </nuxt-link>
                </li>
                <li class="menu-item" :class="$route.path=='/providers' ? 'active' : ''">
                  <nuxt-link to="/providers" class="menu-link">
                    Provider
                  </nuxt-link>
                </li>
                <li class="menu-item" :class="$route.path=='/events' ? 'active' : ''">
                  <nuxt-link to="/events" class="menu-link">
                    Event
                  </nuxt-link>
                </li>
                <li class="menu-item" :class="$route.path=='/trainings' ? 'active' : ''">
                  <nuxt-link to="/trainings" class="menu-link">
                    Training
                  </nuxt-link>
                </li>
              </ul>
            </li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bx-wallet'></i>
                <div data-i18n="Form Layouts">Transaction</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item" :class="$route.path=='/orders' ? 'active' : ''">
                  <nuxt-link to="/orders" class="menu-link">
                    Orders
                  </nuxt-link>
                </li>
              </ul>
              </li>
              <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class='menu-icon tf-icons bx bx-user-voice'></i>
                  <div data-i18n="Form Layouts">Consultant</div>
                </a>
                <ul class="menu-sub">
                  <li class="menu-item" :class="$route.path=='/consultant' ? 'active' : ''">
                    <nuxt-link to="/consultant" class="menu-link">
                      Consultant
                    </nuxt-link>
                  </li>
                  <li class="menu-item" :class="$route.path=='/verification' ? 'active' : ''">
                    <nuxt-link to="/verification" class="menu-link">
                      Verification
                    </nuxt-link>
                  </li>
                </ul>
              </li>
              <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bx-message-square-detail'></i>
                <div data-i18n="Form Layouts">Thread</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item" :class="$route.path=='/report-thread' ? 'active' : ''">
                  <nuxt-link to="/report-thread" class="menu-link">
                    Report Thread
                  </nuxt-link>
                </li>
              </ul>
              </li>
              <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class='menu-icon tf-icons bx bx-news'></i>
                  <div data-i18n="Form Layouts">Insight/Article</div>
                </a>
                <ul class="menu-sub">
                  <li class="menu-item" :class="$route.path=='/insight' ? 'active' : ''">
                    <nuxt-link to="/insight" class="menu-link">Post</nuxt-link>
                  </li>
                  <li class="menu-item" :class="$route.path=='/insight-glossary' ? 'active' : ''">
                    <nuxt-link to="/insight-glossary" class="menu-link">Glossary</nuxt-link>
                  </li>
                  <li class="menu-item" :class="$route.path=='/insight-comment' ? 'active' : ''">
                    <nuxt-link to="/insight-comment" class="menu-link">Comment</nuxt-link>
                  </li>
                </ul>
                </li>
          </ul>
        </aside>
</template>
<script>
    export default {
      name: "side-menu",
      mounted(){
        let menu, animate;
        let layoutMenuEl = document.querySelectorAll('#layout-menu');
        layoutMenuEl.forEach(function (element) {
          menu = new Menu(element, {
            orientation: 'vertical',
            closeChildren: false
          });
          // Change parameter to true if you want scroll animation
          window.Helpers.scrollToActive((animate = false));
          window.Helpers.mainMenu = menu;
        });
  
        // Initialize menu togglers and bind click on each
        let menuToggler = document.querySelectorAll('.layout-menu-toggle');
        menuToggler.forEach(item => {
          item.addEventListener('click', event => {
            event.preventDefault();
            window.Helpers.toggleCollapsed();
          });
        });

        // Display menu toggle (layout-menu-toggle) on hover with delay
        let delay = function (elem, callback) {
          let timeout = null;
          elem.onmouseenter = function () {
            // Set timeout to be a timer which will invoke callback after 300ms (not for small screen)
            if (!Helpers.isSmallScreen()) {
              timeout = setTimeout(callback, 300);
            } else {
              timeout = setTimeout(callback, 0);
            }
          };

          elem.onmouseleave = function () {
            // Clear any timers set to timeout
            document.querySelector('.layout-menu-toggle').classList.remove('d-block');
            clearTimeout(timeout);
          };
        };
        if (document.getElementById('layout-menu')) {
          delay(document.getElementById('layout-menu'), function () {
            // not for small screen
            if (!Helpers.isSmallScreen()) {
              document.querySelector('.layout-menu-toggle').classList.add('d-block');
            }
          });
        }

        // Display in main menu when menu scrolls
        let menuInnerContainer = document.getElementsByClassName('menu-inner'),
          menuInnerShadow = document.getElementsByClassName('menu-inner-shadow')[0];
        if (menuInnerContainer.length > 0 && menuInnerShadow) {
          menuInnerContainer[0].addEventListener('ps-scroll-y', function () {
            if (this.querySelector('.ps__thumb-y').offsetTop) {
              menuInnerShadow.style.display = 'block';
            } else {
              menuInnerShadow.style.display = 'none';
            }
          });
        }

        // Init BS Tooltip
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });

      // Accordion active class
      const accordionActiveFunction = function (e) {
        if (e.type == 'show.bs.collapse' || e.type == 'show.bs.collapse') {
          e.target.closest('.accordion-item').classList.add('active');
        } else {
          e.target.closest('.accordion-item').classList.remove('active');
        }
      };

      const accordionTriggerList = [].slice.call(document.querySelectorAll('.accordion'));
      const accordionList = accordionTriggerList.map(function (accordionTriggerEl) {
        accordionTriggerEl.addEventListener('show.bs.collapse', accordionActiveFunction);
        accordionTriggerEl.addEventListener('hide.bs.collapse', accordionActiveFunction);
      });

      // Auto update layout based on screen size
      window.Helpers.setAutoUpdate(true);

      // Toggle Password Visibility
      window.Helpers.initPasswordToggle();

      // Speech To Text
      window.Helpers.initSpeechToText();

      // Manage menu expanded/collapsed with templateCustomizer & local storage
      //------------------------------------------------------------------

      // If current layout is horizontal OR current window screen is small (overlay menu) than return from here
      if (window.Helpers.isSmallScreen()) {
        return;
      }

      // If current layout is vertical and current window screen is > small

      // Auto update menu collapsed/expanded based on the themeConfig
      window.Helpers.setCollapsed(true, false);
      }
    }
</script>