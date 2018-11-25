<?php $this->assign('description', "Save HALF OFF on School Swag Tags - JUST .11 EACH. Largest Selection. Swag Tags for students and schools"); ?>
<?php $this->assign('title', "Boost Promotions"); ?>

<section>  
    <style>
        body{height: 100%;}
        div.carousel {
            height: 50%;
        }

        div.carousel .item,
        div.carousel .item.active,
        div.carousel .carousel-inner {
            height: 60vh;
        }

        div.carousel .fill {
            width: 100%;
            height: 100%;
            background-position: center;
            background-size: cover;
        }
        div.zoom:hover{
            transform: scale(1.05);
        }
    </style>
    <div class="clearfix"></div>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class=""></li>
            <li data-target="#carousel-example-generic" data-slide-to="1" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item">
                <div class="fill" style="background-image:url('img/slides/1.jpg');"></div>
                <div class="carousel-caption">
                    <!--                    <h2>Caption 1</h2>-->
                </div>
            </div>
            <div class="item active">
                <div class="fill" style="background-image:url('img/slides/2.jpg');"></div>
                <div class="carousel-caption">
                    <!--                    <h2>Caption 2</h2>-->
                </div>
            </div>
            <div class="item">
                <div class="fill" style="background-image:url('img/slides/3.jpg');"></div>
                <div class="carousel-caption">
                    <!--                    <h2>Caption 3</h2>-->
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

    <section class="top-40">
        <div class="container">
            <!-- <?php if (20181125 > date('Ymd')): ?> -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 zoom">
                        <a href="designs/index/12">
                            <?=
                            $this->Html->image('sale.jpg', [
                                'class' => 'img-responsive', 'alt' => 'On Sales Tags']);
                            ?>
                        </a>
                    </div>
                </div> 
                <br><br>
            <!-- <?php endif; ?> -->
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <?=
                    $this->Html->image('recognize-guitar-shape-tags.png', [
                        'class' => 'img-responsive', 'alt' => 'recognize: guitar shape tags']);
                    ?>
                </div>

                <div class="col-md-4 col-sm-6">
                    <a href="#">
                        <?=
                        $this->Html->image('remember-spirit-tags.png', [
                            'class' => 'img-responsive', 'alt' => 'remember: spirit tags']);
                        ?>
                    </a>
                </div>

                <div class="col-md-4 col-sm-6"> 
                    <a href="#">
                        <?=
                        $this->Html->image('reward-bookmark-tags.png', [
                            'class' => 'img-responsive', 'alt' => 'reward: bookmark tags']);
                        ?>
                    </a>
                </div>
            </div>

        </div>
        <br>
        <div class="text-right">
            <br/><br/><Br/><Br/>
            <a href="<?= $this->webroot . "pages/how_to_order" ?>" class="btn btn-link">Learn More</a>
            <a href="<?= $this->webroot . "products" ?>" class="btn btn-primary">START AN ORDER</a>
            <br/><br/><Br/><Br/>
            <br/><br/><Br/><Br/>
        </div>
        </div>
    </section>
</section>
