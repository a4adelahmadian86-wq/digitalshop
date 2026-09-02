jQuery(document).ready(function ($) {

    function goftino(){

        $('#goftino_w').contents().find('#chatbox').html(`
            <style>
              #goftino_w{
                    height: 500px !important;
                }
                #offline-goftino > div{
                    padding: 20px;
                }
                /*#offline-goftino .support-norowz{*/
                /*    background: #ffcfcf;*/
                /*    border-radius: 14px;*/
                /*    padding : 14px;*/
                /*}*/
                #offline-goftino p{
                    font-size: 15px;
                    color: black;
                    display: inline;
                }
                #offline-goftino a{
                    background: white;
                    border: 1px solid #304aca;
                    color: #304aca;
                    width: 98%;
                    padding: 7px 0;
                }
            </style>
            <div id="offline-goftino">
<!--                <div class="support-norowz">-->
<!--                    <p>ساعت پاسخدهی پشتیبانی از 29 اسفند تا 4 فروردین در بازه 10-12 و 20 تا 22 خواهد بود.</p>-->
<!--                </div>-->
<!--            -->
                <div>
                   <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="17.3402" height="18" rx="4" fill="url(#paint0_linear_10074_64947)"/>
                        <path d="M4.8125 9.83L7.53878 12.66L13.001 7" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <defs>
                        <linearGradient id="paint0_linear_10074_64947" x1="8.61053" y1="1.93388" x2="8.61053" y2="8.92562" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#00C853"/>
                        <stop offset="1" stop-color="#008638"/>
                        </linearGradient>
                        </defs>
                    </svg>
                    <p>در صورتی که برای خرید و پشتیبانی سوالی دارید، ابتدا وارد حساب کاربری خود شوید و در بخش تیکت ها، تیکت خود را ثبت کنید.</p>
                </div>
                <a href="/panel/support/ticket-create" target="_blank" class="btn">ثبت تیکت</a>
                
                <div>
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="17.3402" height="18" rx="4" fill="url(#paint0_linear_10074_64947)"/>
                        <path d="M4.8125 9.83L7.53878 12.66L13.001 7" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <defs>
                        <linearGradient id="paint0_linear_10074_64947" x1="8.61053" y1="1.93388" x2="8.61053" y2="8.92562" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#00C853"/>
                        <stop offset="1" stop-color="#008638"/>
                        </linearGradient>
                        </defs>
                    </svg>
                    <p>در صورتی که برای دوره هایی که تهیه کردید سوال دارید از پرسش و پاسخ همان دوره در بخش "دوره های من" پنل کاربری خود اقدام کنید.</p>
                </div>
                <a href="/panel/classes" target="_blank" class="btn">دوره های من</a>
            </div>
        `);

        $('#goftino_w').contents().find('.box-header .title-name').text('ارتباط با پشتیبان ها');
    }

    goftino();

    function checkElement() {
        var element = document.getElementById('offline-goftino');
        if (element) {
            // توقف تکرار کد
            clearInterval(intervalId);
        }else{
            goftino();
        }
    }

// شروع تایمر تکرار کد
    var intervalId = setInterval(checkElement, 1000);



});