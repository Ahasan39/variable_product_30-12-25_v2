<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') - {{$generalsetting->name}}</title>
        <!-- App favicon -->

        <link rel="shortcut icon" href="{{asset($generalsetting->favicon)}}" alt="Super Ecommerce Favicon" />
        <meta name="author" content="Super Ecommerce" />
        <link rel="canonical" href="" />
        @stack('seo') 
        @stack('css')
        <link rel="stylesheet" href="{{asset('frontEnd/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" href="{{asset('frontEnd/css/animate.css')}}" />
        <link rel="stylesheet" href="{{asset('frontEnd/css/all.min.css')}}" />
        <link rel="stylesheet" href="{{asset('frontEnd/css/owl.carousel.min.css')}}" />
        <link rel="stylesheet" href="{{asset('frontEnd/css/owl.theme.default.min.css')}}" />
        <link rel="stylesheet" href="{{asset('frontEnd/css/mobile-menu.css')}}" />
        <link rel="stylesheet" href="{{asset('frontEnd/css/select2.min.css')}}" />
        <!-- toastr css -->
        <link rel="stylesheet" href="{{asset('backEnd/assets/css/toastr.min.css')}}" />

        <link rel="stylesheet" href="{{asset('frontEnd/css/wsit-menu.css')}}" />
        <link rel="stylesheet" href="{{asset('frontEnd/css/style.css')}}" />
        <link rel="stylesheet" href="{{asset('frontEnd/css/responsive.css')}}" />
        <link rel="stylesheet" href="{{asset('frontEnd/css/main.css')}}" />

        <meta name="facebook-domain-verification" content="38f1w8335btoklo88dyfl63ba3st2e" />

        @foreach($pixels as $pixel)
        <!-- Facebook Pixel Code -->
        <script>
            !(function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = "2.0";
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s);
            })(window, document, "script", "https://connect.facebook.net/en_US/fbevents.js");
            fbq("init", "{{{$pixel->code}}}");
            fbq("track", "PageView");
        </script>
        <noscript>
            <img height="1" width="1" style="display: none;" src="https://www.facebook.com/tr?id={{{$pixel->code}}}&ev=PageView&noscript=1" />
        </noscript>
        <!-- End Facebook Pixel Code -->
        @endforeach
        
      @foreach ($gtm_code as $gtm)
   
         <!--Google tag (gtag.js) -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    "gtm.start": new Date().getTime(),
                    event: "gtm.js"
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != "dataLayer" ? "&l=" + l : "";
                j.async = true;
                j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
                f.parentNode.insertBefore(j, f);
            })
            (window, document, "script", "dataLayer", "GTM-{{ $gtm->code }}");
        </script>
         <!--End Google Tag Manager -->
    @endforeach
        <style>
            .details_right{
                padding-bottom: 18px !important;
                height: fit-content;
            }
        </style>
    </head>
    <body class="gotop">
       <!-- Google Tag Manager (noscript) -->
    @foreach ($gtm_code as $gtm)
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-{{ $gtm->code }}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  @endforeach
<!-- End Google Tag Manager (noscript) -->
        
        <style>
        #chat-button {
    position: fixed;
    right: 20px; /* <- changed from left */
    bottom: 20px;
    background-color: #00657c;
    color: white;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    text-align: center;
    line-height: 50px;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    z-index: 9999;
}

#chat-box {
    position: fixed;
    right: 20px; /* <- changed from left */
    bottom: 80px;
    width: 300px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.15);
    z-index: 9999;
    overflow: hidden;
    font-family: Arial, sans-serif;
}



.chat-header {
    background-color: #00657c;
    color: white;
    padding: 10px;
    font-weight: bold;
}

.chat-body {
    padding: 10px;
    height: 150px;
    overflow-y: auto;
}

.chat-footer {
    padding: 10px;
    border-top: 1px solid #ddd;
}


.chat-input-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.chat-input-group input {
    flex: 1;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
}
 
 .chat-input-group button {
    background-color: #00657c;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.chat-input-group button:hover {
    background-color: #e14a00;
}


.chat-footer input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}

.chat-body div {
    font-size: 14px;
}


        </style>
<!-- Chat Button -->
<div id="chat-button" onclick="toggleChat()" style="
    position: fixed;
    bottom: 60px;
    right: 30px;
    background: #007bff;
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 28px;
    line-height: 50px;
    text-align: center;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    z-index: 1000;
">💬</div>

<!-- Chat Box -->
<div id="chat-box" style="
    display: none;
    max-height: 400px; 
    overflow-y: auto; 
    border: 1px solid #ccc; 
    width: 350px; 
    position: fixed;
    bottom: 90px;
    right: 30px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    font-family: Arial, sans-serif;
    z-index: 1000;
">
    <div class="chat-header" style="
        background:#007bff; 
        color:#fff; 
        padding: 12px 16px; 
        font-weight: bold;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        user-select:none;
    ">
        Chat
        <span onclick="toggleChat()" style="
            float: right;
            cursor: pointer;
            font-weight: normal;
            font-size: 20px;
            line-height: 20px;
            margin-top: -2px;
        ">&times;</span>
    </div>
    <div class="chat-body" id="chat-body" style="
        padding: 12px 16px; 
        min-height: 150px;
        max-height: 250px;
        overflow-y: auto;
        background: #f9f9f9;
        font-size: 14px;
        line-height: 1.4;
        color: #333;
    ">
        <p>Hello! How can we help you?</p>
    </div>
    <div class="chat-footer" style="
        padding: 12px 16px; 
        border-top: 1px solid #ccc;
    ">
        <div class="chat-input-group" style="
            display: flex; 
            flex-direction: column; 
            gap: 10px;
        ">
            <input type="text" id="guest-name" placeholder="Your Name" style="
                padding: 10px; 
                width: 100%; 
                border: 1px solid #ccc; 
                border-radius: 4px;
                font-size: 14px;
            " />
            <input type="email" id="guest-email" placeholder="Your Email" style="
                padding: 10px; 
                width: 100%; 
                border: 1px solid #ccc; 
                border-radius: 4px;
                font-size: 14px;
            " />
            <textarea id="chat-input" placeholder="Type a message..." onkeydown="handleKey(event)" style="
                padding: 10px; 
                width: 100%; 
                border: 1px solid #ccc; 
                border-radius: 4px;
                font-size: 14px;
                resize: vertical;
                min-height: 60px;
            "></textarea>
            <button onclick="sendMessage()" style="
                padding: 10px; 
                background:#007bff; 
                color:#fff; 
                border:none; 
                cursor:pointer; 
                border-radius: 4px;
                font-weight: bold;
                transition: background-color 0.3s ease;
                width:100%;
            " 
            onmouseover="this.style.backgroundColor='#0056b3'" 
            onmouseout="this.style.backgroundColor='#007bff'">
                Send
            </button>
        </div>
    </div>
</div>


<script>
    function toggleChat() {
        const chatBox = document.getElementById('chat-box');
        chatBox.style.display = (chatBox.style.display === 'block') ? 'none' : 'block';
    }

    function handleKey(event) {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
        }
    }

    function sendMessage() {
        let name = document.getElementById('guest-name').value.trim();
        let email = document.getElementById('guest-email').value.trim();
        const content = document.getElementById('chat-input').value.trim();

        // একবার নাম ও ইমেইল দিলে পরের বার শুধু মেসেজ ইনপুট দেখাতে
        if (!name || !email) {
            alert("Please enter your name and email.");
            return;
        }
        if (!content) {
            alert("Please type your message.");
            return;
        }

        // নাম আর ইমেইল input গুলো hide করে রাখি পরে মেসেজ পাঠাতে সুবিধা হয়
        document.getElementById('guest-name').style.display = 'none';
        document.getElementById('guest-email').style.display = 'none';

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("{{ route('guest.message') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify({
                guest_name: name,
                guest_email: email,
                content: content
            })
        })
        .then(res => {
            if (!res.ok) return res.text().then(t => {throw new Error(t)});
            return res.json();
        })
        .then(() => {
            addMessageToChat("You", content, true);
            document.getElementById('chat-input').value = "";
            fetchMessages();
        })
        .catch(err => {
            alert("Error sending message.\n" + err.message);
            console.error(err);
        });
    }

    function renderMessages(messages) {
        const chatBody = document.getElementById('chat-body');
        chatBody.innerHTML = '';

        if (!messages.length) {
            chatBody.innerHTML = '<p>No messages yet. Start the conversation!</p>';
            return;
        }

        messages.forEach(msg => {
            if (msg.content) {
                addMessageToChat(msg.sender, msg.content, msg.sender.toLowerCase() === document.getElementById('guest-name').value.trim().toLowerCase());
            }
            if (msg.admin_reply) {
                addMessageToChat('Admin', msg.admin_reply, false, true);
            }
        });

        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function addMessageToChat(sender, message, isUser = false, isAdmin = false) {
        const chatBody = document.getElementById('chat-body');
        const p = document.createElement('p');
        p.textContent = sender + ': ' + message;

        if (isUser) {
            p.style.textAlign = 'right';
            p.style.backgroundColor = '#007bff';
            p.style.color = '#fff';
            p.style.margin = '5px 0 5px auto';
        } else if (isAdmin) {
            p.style.textAlign = 'left';
            p.style.backgroundColor = '#f1f0f0';
            p.style.color = '#333';
            p.style.margin = '5px auto 5px 0';
        } else {
            p.style.textAlign = 'left';
            p.style.backgroundColor = '#e2e2e2';
            p.style.color = '#000';
            p.style.margin = '5px auto 5px 0';
        }

        p.style.padding = '8px 12px';
        p.style.borderRadius = '12px';
        p.style.maxWidth = '75%';

        chatBody.appendChild(p);
    }

    function fetchMessages() {
        const email = document.getElementById('guest-email').value.trim();
        const nameInputVisible = document.getElementById('guest-name').style.display !== 'none';

        // email না থাকলে আর ফেচ করব না
        if (!email || nameInputVisible) return;

        fetch("/messages?guest_email=" + encodeURIComponent(email))
        .then(res => {
            if (!res.ok) throw new Error("Server error");
            return res.json();
        })
        .then(data => {
            renderMessages(data);
        })
        .catch(err => {
            console.error(err);
            // চাইলে ইউজারকে দেখানো যায়
        });
    }

    // Refresh messages every 15 seconds to save resources
    setInterval(fetchMessages, 15000);

    // ইউজার যখন নাম/ইমেইল দেয়, তখন মেসেজ ফেচ করো
    document.getElementById('guest-email').addEventListener('blur', fetchMessages);
</script>








        @php $subtotal = Cart::instance('shopping')->subtotal(); @endphp
        <div class="mobile-menu">
                <div class="mobile-menu-logo">
                    <div class="logo-image">
                        <img src="{{asset($generalsetting->white_logo)}}" alt="" width="150" height="40" />
                    </div>
                    <div class="mobile-menu-close">
                        <i class="fa fa-times"></i>
                    </div>
                </div>
                <ul class="first-nav">
                    @foreach($menucategories as $scategory)
                    <li class="parent-category">
                        <a href="{{url('category/'.$scategory->slug)}}" class="menu-category-name">
                            <img src="{{asset($scategory->image)}}" alt="" class="side_cat_img" loading="lazy" />
                            {{$scategory->name}}
                        </a>
                        @if($scategory->subcategories->count() > 0)
                        <span class="menu-category-toggle">
                            <i class="fa fa-chevron-down"></i>
                        </span>
                        @endif
                        <ul class="second-nav" style="display: none;">
                            @foreach($scategory->subcategories as $subcategory)
                            <li class="parent-subcategory">
                                <a href="{{url('subcategory/'.$subcategory->slug)}}" class="menu-subcategory-name">{{$subcategory->subcategoryName}}</a>
                                @if($subcategory->childcategories->count() > 0)
                                <span class="menu-subcategory-toggle"><i class="fa fa-chevron-down"></i></span>
                                @endif
                                <ul class="third-nav" style="display: none;">
                                    @foreach($subcategory->childcategories as $childcat)
                                    <li class="childcategory"><a href="{{url('products/'.$childcat->slug)}}" class="menu-childcategory-name">{{$childcat->childcategoryName}}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </div>
        @include('frontEnd.layouts.header')
        <div id="content">
            @yield('content')
        </div>
            <!-- content end -->
        @include('frontEnd.layouts.footer')
        

        
        

        <div class="scrolltop" style="">
            <div class="scroll">
                <i class="fa fa-angle-up"></i>
            </div>
        </div>

        <!-- /. fixed sidebar -->

        <div id="custom-modal"></div>
        <div id="page-overlay"></div>
        <div id="loading"><div class="custom-loader"></div></div>

        <script src="{{asset('frontEnd/js/jquery-3.6.3.min.js')}}"></script>
        <script src="{{asset('frontEnd/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('frontEnd/js/owl.carousel.min.js')}}"></script>
        <script src="{{asset('frontEnd/js/mobile-menu.js')}}"></script>
        <script src="{{asset('frontEnd/js/wsit-menu.js')}}"></script>
        <script src="{{asset('frontEnd/js/mobile-menu-init.js')}}"></script>
        <script src="{{asset('frontEnd/js/wow.min.js')}}"></script>
        <script>
            new WOW().init();
        </script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!--chat box-->
<!--chat box-->


        <!-- feather icon -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
        <script>
            feather.replace();
        </script>
        <script src="{{asset('backEnd/assets/js/toastr.min.js')}}"></script>
        {!! Toastr::message() !!} @stack('script')
        <script>
            $(".quick_view").on("click", function () {
                var id = $(this).data("id");
                $("#loading").show();
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('quickview')}}",
                        success: function (data) {
                            if (data) {
                                $("#custom-modal").html(data);
                                $("#custom-modal").show();
                                $("#loading").hide();
                                $("#page-overlay").show();
                            }
                        },
                    });
                }
            });
        </script>
        <!-- quick view end -->
        <!-- cart js start -->
        <script>
            $(".addcartbutton").on("click", function () {
                var id = $(this).data("id");
                var qty = 1;
                if (id) {
                    $.ajax({
                        cache: "false",
                        type: "GET",
                        url: "{{url('add-to-cart')}}/" + id + "/" + qty,
                        dataType: "json",
                        success: function (data) {
                            if (data) {
                                toastr.success('Success', 'Product add to cart successfully');
                                return cart_count() + mobile_cart();
                            }
                        },
                    });
                }
            });
            $(".cart_store").on("click", function () {
                var id = $(this).data("id");
                var qty = $(this).parent().find("input").val();
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id, qty: qty ? qty : 1 },
                        url: "{{route('cart.store')}}",
                        success: function (data) {
                            if (data) {
                                toastr.success('Success', 'Product add to cart succfully');
                                return cart_count() + mobile_cart();
                            }
                        },
                    });
                }
            });

            $(".cart_remove").on("click", function () {
                var id = $(this).data("id");
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('cart.remove')}}",
                        success: function (data) {
                            if (data) {
                                $(".cartlist").html(data);
                                return cart_count() + mobile_cart() + cart_summary();
                            }
                        },
                    });
                }
            });

            $(".cart_increment").on("click", function () {
                var id = $(this).data("id");
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('cart.increment')}}",
                        success: function (data) {
                            if (data) {
                                $(".cartlist").html(data);
                                return cart_count() + mobile_cart();
                            }
                        },
                    });
                }
            });

            $(".cart_decrement").on("click", function () {
                var id = $(this).data("id");
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: { id: id },
                        url: "{{route('cart.decrement')}}",
                        success: function (data) {
                            if (data) {
                                $(".cartlist").html(data);
                                return cart_count() + mobile_cart();
                            }
                        },
                    });
                }
            });

            function cart_count() {
                $.ajax({
                    type: "GET",
                    url: "{{route('cart.count')}}",
                    success: function (data) {
                        if (data) {
                            $("#cart-qty").html(data);
                        } else {
                            $("#cart-qty").empty();
                        }
                    },
                });
            }
            function mobile_cart() {
                $.ajax({
                    type: "GET",
                    url: "{{route('mobile.cart.count')}}",
                    success: function (data) {
                        if (data) {
                            $(".mobilecart-qty").html(data);
                        } else {
                            $(".mobilecart-qty").empty();
                        }
                    },
                });
            }
            function cart_summary() {
                $.ajax({
                    type: "GET",
                    url: "{{route('shipping.charge')}}",
                    dataType: "html",
                    success: function (response) {
                        $(".cart-summary").html(response);
                    },
                });
            }
        </script>
        <!-- cart js end -->
        <script>
            let searchTimeout;
            $(".search_click, .msearch_click").on("keyup change", function () {
                var searchInput = $(this);
                var keyword = searchInput.val();
                
                clearTimeout(searchTimeout);
                
                searchTimeout = setTimeout(function() {
                    $.ajax({
                        type: "GET",
                        data: { keyword: keyword },
                        url: "{{route('livesearch')}}",
                        success: function (products) {
                            if (products) {
                                if(searchInput.hasClass('msearch_click')) {
                                     $("#loading").hide();
                                }
                                $(".search_result").html(products);
                            } else {
                                $(".search_result").empty();
                            }
                        },
                    });
                }, 300); // Debounce delay 300ms
            });
        </script>
        <!-- search js start -->
        <script></script>
        <script></script>
        <script>
            $(".district").on("change", function () {
                var id = $(this).val();
                $.ajax({
                    type: "GET",
                    data: { id: id },
                    url: "{{route('districts')}}",
                    success: function (res) {
                        if (res) {
                            $(".area").empty();
                            $(".area").append('<option value="">Select..</option>');
                            $.each(res, function (key, value) {
                                $(".area").append('<option value="' + key + '" >' + value + "</option>");
                            });
                        } else {
                            $(".area").empty();
                        }
                    },
                });
            });
        </script>
        <script>
            $('.toggle').on('click', function(e) {
                e.preventDefault();
                requestAnimationFrame(() => {
                    $('.mobile-menu').addClass('active');
                    $("#page-overlay").show();
                });
            });

            $('.mobile-menu-close, .mobile-menu-overlay').on('click', function(e) {
                e.preventDefault();
                requestAnimationFrame(() => {
                    $('.mobile-menu').removeClass('active');
                    $("#page-overlay").hide();
                });
            });

            $("#page-overlay").on("click", function () {
                $("#page-overlay").hide();
                $(".mobile-menu").removeClass("active");
                $(".feature-products").removeClass("active");
            });

            $(".mobile-menu-close").on("click", function () {
                $("#page-overlay").hide();
                $(".mobile-menu").removeClass("active");
            });

            $(".mobile-filter-toggle").on("click", function () {
                $("#page-overlay").show();
                $(".feature-products").addClass("active");
            });

            $('.menu-category-link').on('click', function(e){
                // Allow default link behavior
            });

            // Optimized toggle handler
            function handleToggle(e) {
                e.preventDefault();
                e.stopPropagation();
                var $this = $(this);
                // Use requestAnimationFrame for visual updates
                requestAnimationFrame(() => {
                   $this.toggleClass('active');
                   // Toggle class on parent for CSS-driven visibility
                   $this.parent().toggleClass('open'); 
                });
            }

            $('.menu-category-toggle').on('click', handleToggle);
            $('.menu-subcategory-toggle').on('click', handleToggle);
        </script>
        <script>
            $(document).ready(function () {
                $(".parent-category").each(function () {
                    const menuCatToggle = $(this).find(".menu-category-toggle");
                    const secondNav = $(this).find(".second-nav");

                    menuCatToggle.on("click", function () {
                        menuCatToggle.toggleClass("active");
                        secondNav.slideToggle("fast");
                        $(this).closest(".parent-category").toggleClass("active");
                    });
                });
                $(".parent-subcategory").each(function () {
                    const menuSubcatToggle = $(this).find(".menu-subcategory-toggle");
                    const thirdNav = $(this).find(".third-nav");

                    menuSubcatToggle.on("click", function () {
                        menuSubcatToggle.toggleClass("active");
                        thirdNav.slideToggle("fast");
                        $(this).closest(".parent-subcategory").toggleClass("active");
                    });
                });
            });
        </script>

        <script>
            // Check if menu element exists before initializing
            var menuElement = document.querySelector("#menu");
            if (menuElement) {
                var menu = new MmenuLight(menuElement, "all");

                var navigator = menu.navigation({
                    selectedClass: "Selected",
                    slidingSubmenus: true,
                    // theme: 'dark',
                    title: "ক্যাটাগরি",
                });

                var drawer = menu.offcanvas({
                    // position: 'left'
                });

                //  Open the menu.
                var menuLink = document.querySelector('a[href="#menu"]');
                if (menuLink) {
                    menuLink.addEventListener("click", (evnt) => {
                        evnt.preventDefault();
                        drawer.open();
                    });
                }
            }
        </script>

        <script>
            // document.addEventListener("DOMContentLoaded", function () {
            //     window.addEventListener("scroll", function () {
            //         if (window.scrollY > 200) {
            //             document.getElementById("navbar_top").classList.add("fixed-top");
            //         } else {
            //             document.getElementById("navbar_top").classList.remove("fixed-top");
            //             document.body.style.paddingTop = "0";
            //         }
            //     });
            // });
            /*=== Main Menu Fixed === */
            // document.addEventListener("DOMContentLoaded", function () {
            //     window.addEventListener("scroll", function () {
            //         if (window.scrollY > 0) {
            //             document.getElementById("m_navbar_top").classList.add("fixed-top");
            //             // add padding top to show content behind navbar
            //             navbar_height = document.querySelector(".navbar").offsetHeight;
            //             document.body.style.paddingTop = navbar_height + "px";
            //         } else {
            //             document.getElementById("m_navbar_top").classList.remove("fixed-top");
            //             // remove padding top from body
            //             document.body.style.paddingTop = "0";
            //         }
            //     });
            // });
            /*=== Main Menu Fixed === */

            $(window).scroll(function () {
                if ($(this).scrollTop() > 50) {
                    $(".scrolltop:hidden").stop(true, true).fadeIn();
                } else {
                    $(".scrolltop").stop(true, true).fadeOut();
                }
            });
            $(function () {
                $(".scroll").click(function () {
                    $("html,body").animate({ scrollTop: $(".gotop").offset().top }, "1000");
                    return false;
                });
            });
        </script>
        <script>
            $(".filter_btn").click(function(){
               $(".filter_sidebar").addClass('active');
               $("body").css("overflow-y", "hidden");
            })
            $(".filter_close").click(function(){
               $(".filter_sidebar").removeClass('active');
               $("body").css("overflow-y", "auto");
            })
        </script>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K29C9BKJ"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    </body>
</html>
