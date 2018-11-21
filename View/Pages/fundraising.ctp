<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
<style type="text/css">

    .teal-bg{
        background-color: #4dc5b1!important;
    }
    .teal-color{
        color: #4dc5b1;
    }
    .unplug{
        margin: 0 auto;
        margin-top: -240px;
        margin-bottom: 20px;
    }
    .box-picture{
        padding: 7px;
    }
    .box1{
        background-image: url('../img/fundraising/park.png');
        background-repeat: no-repeat;
        background-size: cover;
        width: auto;
        height: auto;
    }
    .inside-box h1{

        font-size: 49px;
        font-style: normal;
        font-variant: normal;
        font-weight: 700;
        color:#fff;
        font: bold 6em/1em Helvetica, Verdana, sans-serif;
        text-shadow: 2px 4px 10px #000;
    }

    .semi-blue{
        background-color: #94ddd1;
    }

    .booster{
        margin: 0 auto;
        padding: 20px;
        width: 50%;

    }

    .easyImage{
        margin: 0 auto;
    }

    .box3{
        margin: 0px 150px  0px 150px;
        background-color: #ffeefe;
        padding: 30px;
    }

    .box4{
        margin:0 150px;
        padding: 50px 0; 
    }

    .button {
        display: inline-block;
        color: #fff;
        text-shadow: 0 0 2px rgba(0,0,0,.3);
        font-family: sans-serif;
        box-shadow:
            inset 0 0 2px 0 rgba(255,255,255,.4),
            inset 0 0 3px 0 rgba(0,0,0,.4),
            inset 0 0 3px 5px rgba(0,0,0,.05),
            2px 2px 4px 0 rgba(0,0,0,.25);
        border-radius: 10px;
        padding: 8px 16px;;
        font-size: 40px;
        line-height: 40px;
        position: relative;
    }
    .button.red {  background: linear-gradient(yellow, red); }
    .button.green { background: linear-gradient(#fca21f, #fdcf13); }
    .button.blue { background: #4A90E2; }
    .button:before, .button:after {
        content: '';
        display: block;
        position: absolute;
        left: 2px;
        right: 2px;
        height: 3px;
    }
    .button:before {
        top: 0;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        background: rgba(255,255,255,.6);
        box-shadow: 0 1px 2px 0 rgba(255,255,255,.6);
    }
    .button:after {
        bottom: 0;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
        background: rgba(0,0,0,.15);
        box-shadow: 0 -1px 2px 0 rgba(0,0,0,.15);
    }

</style>

<section>
    <div class="container">
        <div class="semi-blue">
            <div class="teal-bg">
                <div style="background-image:url(<?= $this->webroot ?>img/fundraising/clouds.jpg);background-size:cover; height:270px">

                </div>   
                <?= $this->Html->Image('fundraising/unplug-logo.png', ['class' => 'img-responsive unplug', "alt"=>"Unplugged Challenge Logo"]) ?>

                <div class="row" style="margin:0px!important">
                    <div class="col-md-6" style="padding: 0px; backgroundckground-color: #fff">
                        <div class="box-picture">
                            <?= $this->Html->Image('fundraising/image01.jpg', ['class' => 'img-responsive', "alt"=>"Kids busy at phones"]) ?>
                        </div>

                    </div>
                    <div class="col-md-6"  style="padding: 0px;background-color: #fff">
                        <div class="box-picture">
                            <?= $this->Html->Image('fundraising/image02.jpg', ['class' => 'img-responsive', "alt"=>"Kids playing outdoors"]) ?>
                        </div>
                    </div>
                </div>

                <div class="box1">
                    <div class="row">
                        <div class="col-md-6"><?= $this->Html->Image('fundraising/less.png', [
                            'class' => 'img-responsive easyImage', "alt"=>"'Less of this'"]) ?></div>
                        <div class="col-md-6"><?= $this->Html->Image('fundraising/more.png', [
                            'class' => 'img-responsive easyImage', "alt"=>"'More of this'"]) ?></div>
                    </div>
                    <div class="img">
                        <div class="inside-box text-center" >
                            <br><br><br>
                            <h3 style="color:#fff; text-shadow: 2px 2px 7px #000;">Raise funds by Encouraging kids to have Fun!!</h3>
                            <h1>EARN $26,220</h1>
                            <h3  style="color:#fff;text-shadow: 2px 2px 7px #000;">If 50% of your students each sold 4 kits</h3>
                            <h4 style="color:#000;">*Calculation are based or school size of 750 students</h4>
                        </div>
                        <br><br>
                        <div class="img">
                            <p class="text-center">
                                <a href="http://unpluggedchallenge.com/"  target="_blank" class="button red btn-lg">Learn More</a>
                            </p>
                        </div>
                    </div>
                    <br><br><br>
                </div>
            </div>

            <div class="box2 ">
                <div class="text-center">

                    <?= $this->Html->Image('fundraising/booster.png', ['class' => 'img-responsive booster', "alt"=>"BoosterTags Logo"]) ?>

                    <h1>Hassle-Free Custom Tag Sales & Donation Collection</h1>
                    <p>Booster Tags makes it risk-free to raise funds and build school</p><br>
                </div>
                <div class="row">
                    <div class="img">
                        <div class="col-md-4">
                            <p class="text-right "><span class="h3">Raise Funds<br></span>Sell Customized Spirit Tags to students, faculty, <br>family and community, There’s no limit <br> to your potentials!</p>
                            <br><br><br>
                            <p class="text-right "><span class="h3">Raise School Spirit<br></span>Customized Spirit Tags will raise school spirit and create connections with families and the cummunity.</p>
                        </div>
                        <div class="col-lg-4"><img src="<?= $this->webroot ?>img/fundraising/luggagebag.png" alt="Bags with Customized Spirit Tags attached to them" class="img-responsive"></div>
                        <div class="col-lg-4">
                            <p class="text-left "><span class="h3">Fast, Easy and Fun<br></span>It’s easy to create your customized digital <br>catalog and share it online.</p>
                            <br><br><br>
                            <p class="text-left "><span class="h3">No Inventory<br></span>Sell Customized Spirit Tags without buying inventory, collecting money or shipping orders.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="img">
                <div class="box3">
                    <h2 class="text-center">3 Easy Steps to Make your Booster Tags Fundaiser Successful</h2>
                    <div class="row">   
                        <div class="col-md-4 text-center">
                            <?= $this->Html->Image('fundraising/catalogDesign.png', ['class' => 'img-responsive easyImage', "alt" => "Catalog Design"]) ?>
                            <p class="h5"><b>Create Your Catalog</b></p>
                            <p>Work with our design team to create 10-15 custom spirit tag designs</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <?= $this->Html->Image('fundraising/promoteFundraiser.png', ['class' => 'img-responsive easyImage', "alt" => "Promote Fundraiser"]) ?>
                            <p class="h5"><b>Promote Your Fundraiser</b></p>
                            <p>Use social media to tell students, administrators, family and the community about your fundraiser</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <?= $this->Html->Image('fundraising/collectFund.png', ['class' => 'img-responsive easyImage', "alt"=>"Collect Funds"]) ?>
                            <p class="h5"><b>Collect Your Funds</b></p>
                            <p>No Merchandise or money to handle. Booster Tags will fulfill each order and send along your portion of the sales generated</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box4">
                <h2 class="text-center">Easy Tools that Work For You</h2>
                <div class="row">
                    <div class="col-md-2">
                        <?= $this->Html->Image('fundraising/socialSharing.png', ['class' => 'img-responsive easyImage', "alt"=> "Social Sharing"]) ?>
                    </div>
                    <div class="col-md-4">
                        <p class="h5"><b>Social Sharing Tools</b></p>
                        <p>Supporters can share with their extended network on social media, increasing fundraising potential.</p>
                    </div>
                    <div class="col-md-2">
                        <?= $this->Html->Image('fundraising/donationTool.png', ['class' => 'img-responsive easyImage', "alt"=> "Donation Tool"]) ?>
                    </div>
                    <div class="col-md-4">
                        <p class="h5"><b>Donations Tool</b></p>
                        <p>Supporters can choose to add an additional donation during checkout.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <?= $this->Html->Image('fundraising/digitalCatalog.png', ['class' => 'img-responsive easyImage', "alt"=> "Digital Catalog"]) ?>
                    </div>
                    <div class="col-md-4">
                        <p class="h5"><b>Custom Digital Catalog</b></p>
                        <p>Include up to 15 custom designed Spirit Tags to be included in your online catalog.</p>
                    </div>
                    <div class="col-md-2">
                        <?= $this->Html->Image('fundraising/dashboard.png', ['class' => 'img-responsive easyImage', "alt"=> "Dashboard"]) ?>
                    </div>
                    <div class="col-md-4">
                        <p class="h5"><b>Online Tracking</b></p>
                        <p>Check your fundraising progress thru your customized Dashboard.</p>
                    </div>
                </div>

                <br><br>
                <p class="text-center"><a href="http://boostertags.com/" target="_blank" class="button green btn-lg">Learn More</a></p>
                <br><br>
            </div>
        </div>
    </div>
</div>
</section>
