<?php $this->assign('title', $contest['Contest']['contests_description'] . " — Boost Promotions"); ?>
<div class="container">
    <div class="page-header">
        <h1>BOOSTPROMOTIONS.COM GIVEAWAY</h1>
    </div>
    <div class="col-md-6">
        <iframe width="510px" height="1500px" frameBorder="0" seamless="seamless" 
                src="https://boostpromotions.com/gowin/widget/index/<?= $contest['Contest']['id']; ?>" 
                id="iFrame1" 
                sandbox="allow-scripts allow-forms allow-same-origin allow-top-navigation allow-popups"  >
        </iframe>
    </div>
    <style>
        #wlist span {
            width: 30px;
            margin-right: 20px;
            height: 30px;
            display: inline-block;
            vertical-align: middle;
            padding: 6px 0 0 6px;
            color: white;
        }
    </style>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $contest['Contest']['contests_description']; ?>
            </div>
            <div class="panel-body">
                <h3>1 LUCKY WINNER</h3>
                <div id="wlist">
                    <h5><span style="background-color: #004C70">1st</span><?= $contest['Contest']['prize']; ?></h5>
                    <?php /*
                      <h5><span style="background-color: #006495">2nd</span>$400 Sign Here Vinyl Store Credit</h5>
                      <h5><span style="background-color: #0093D1">3rd</span>$100 PayPal Cash</h5>
                     */
                    ?>
                </div>
                <hr/>
                <h3>ABOUT BOOST PROMOTIONS</h3>
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class=""></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <a href="index.php?main_page=teacherkits"><img src="https://boostpromotions.com/images/1.jpg" alt="Click here to look at our Teacher Kits!" /></a>
                            <div class="carousel-caption">

                            </div>
                        </div>
                        <div class="item">
                            <a href="index.php?main_page=index&cPath=49_33"><img src="https://boostpromotions.com/images/2.jpg" alt="Spirit Tags" /></a>
                            <div class="carousel-caption">

                            </div>
                        </div>
                        <div class="item">
                            <a href="index.php?main_page=swag_tags"><img src="https://boostpromotions.com/images/3.jpg" alt="Attendance Tags" /></a>
                            <div class="carousel-caption">

                            </div>
                        </div>
                    </div>

                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                        <span class="icon-prev"></span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                        <span class="icon-next"></span>
                    </a>
                </div>

                <p>
                    Boost Promotions is a family run company with a mission to enrich the lives of children and their families through our unique youth programs designed to educate and motivate kids through  positive recognition for their achievements.
                </p><p>
                    Our Swag Tags are a unique alternative to sugary treats or cheap trinkets that have no lasting value.  Swag Tags help children to reach goals and develop self-esteem by providing positive reinforcement for their achievements.  Swag Tags also help increase participation and promote school spirit among students, their families and administrators.
                </p><p>
                    Hundreds of schools and communities across the country have implemented our programs with great success for topics such as bullying, drugs, attendance, academics, child obesity, excessive screen time, and technology dependence.
                </p>
                <hr/>
                <small><?php // This giveaway ends Sunday, November 20, 2016 at Midnight MT.                     ?>
                    We never rent or sell your information.</small>
            </div>
        </div>
    </div>
</div>
