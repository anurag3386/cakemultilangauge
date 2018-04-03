<?php echo $this->Html->css('elite-innerpage-custom'); ?>
<article id="post-41" class="post-41 page type-page status-publish hentry">
    <div class="entry-content">
        <div class="div-outer inn-container">
            <div class="div-clear">

                <div class="div-inner">
                    <div class="common-content-left floatL">
                        <div class="box-shadow">
                            <div class="common-box box-shadow margin0">
                                <div class="common-box-one">
                                    <h1>
                                        <?= $this->Html->image('../images/icon-report.png', ['alt' => $product_name.' Testimonials', 'title' => $product_name.' Testimonials',]); ?>
                                        <!-- <img src="https://216.245.193.174/images/icon-report.png" alt="Our Reports" title="Our Reports"> -->
                                        <?= $product_name.' Testimonials' ?>
                                    </h1>
                                    <div class="common-box">
                                        <div class="common-box-content">
                                            <ul>
                                                <li>
                                                    <!-- <span class="head" style="margin-left:0px;"><a name="astropage-faq" style="text-decoration:none;"><?php //echo $product_name; ?></a></span> -->
                                                    <?php if (!empty($user_testimonial_details) && is_array($user_testimonial_details)) {
                                                        foreach ($user_testimonial_details as $key => $value) { ?>
                                                            <ul>
                                                                <li>
                                                                    <span>
                                                                        <?= $this->Html->image('../images/open-quot.png', ['width' => '18px', 'style' => ['border : none; box-shadow : none;']]); ?>
                                                                    </span>
                                                                    <div class="clear"></div>
                                                                    <div style="margin-left:25px;margin-right:38px; margin-top:-9px;">
                                                                        <?= $value['content']; ?>
                                                                    </div>
                                                                    <span>
                                                                        <?= $this->Html->image('../images/close-quot.png', ['width' => '18px', 'style' => ['border : none; box-shadow : none; float : right; margin-top : -15px;']]); ?>
                                                                    </span>
                                                                    <div class="clear"></div>
                                                                    <div class="testimonials-name-bold" style="float:right;">
                                                                        <b>
                                                                            <?php
                                                                                $userdetail = ucwords($value['first_name'].' '.$value['last_name']);
                                                                                if (!empty($value['user_profile'])) {
                                                                                    $userdetail .= ', '.$value['user_profile'];
                                                                                }
                                                                                if (!empty($value['website'])) {
                                                                                    $userdetail .= ' at '.$value['website'];
                                                                                }
                                                                                echo $userdetail;
                                                                            ?>
                                                                        </b>
                                                                    </div>
                                                                    <br>
                                                                    <hr>
                                                                </li>
                                                            </ul>
                                                    <?php } } ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="common-box box-shadow"> </div>-->
                    </div>
                    <!-- <div class="common-content-right floatR">
                        <div class="sml-common-box box-shadow margin0">
                            <h2>Testimonials</h2>
                            <ul class="submenu-two " id="listPersonalizedReading">
                                <li>
                                    <a href="#" class="menuitem submenuheader selected" headerindex="0h"><span class="accordprefix"></span>Astropage<span class="accordsuffix"></span></a>
                                    <dd class="accordian-content" contentindex="0c" style="display: block;">
                                        <a href="https://216.245.193.174/testimonials/astropage/en/" style="background:none;font-weight:normal;padding-top:2px;">Astropage Testimonials</a>
                                    </dd>
                                </li>
                                <li>
                                    <a href="#" class="menuitem submenuheader" headerindex="1h"><span class="accordprefix"></span>Golden Circle<span class="accordsuffix"></span></a>
                                    <dd class="accordian-content" contentindex="1c" style="display: none;">
                                        <a href="https://216.245.193.174/testimonials/golden-circle/en/" style="background:none;font-weight:normal;padding-top:2px;">Golden Circle Testimonials</a>
                                    </dd>
                                </li>
                                <li>
                                    <a href="#" class="menuitem submenuheader" headerindex="2h"><span class="accordprefix"></span>Astrology Software<span class="accordsuffix"></span></a>
                                    <dd class="accordian-content" contentindex="2c" style="display: none;">
                                        <a href="https://216.245.193.174/testimonials/software/horoscope-interpreter/en/" style="background:none;font-weight:normal;padding-top:2px;">Horoscope Interpreter </a>
                                        <a href="https://216.245.193.174/testimonials/software/astrology-for-lovers/en/" style="background:none;font-weight:normal;padding-top:2px;">Astrology For Lovers </a>
                                        <a href="https://216.245.193.174/testimonials/software/astrological-calendar/en/" style="background:none;font-weight:normal;padding-top:2px;">Astrological Calendar</a> 
                                    </dd>
                                </li>
                                <li>
                                    <a href="#" class="menuitem submenuheader" headerindex="3h"><span class="accordprefix"></span>Astrology Reading<span class="accordsuffix"></span></a>
                                    <dd class="accordian-content" contentindex="3c" style="display: none;">
                                        <a href="https://216.245.193.174/testimonials/reports/essential-year-ahead-prediction/en/" style="background:none;font-weight:normal;padding-top:2px;">Essential Year Ahead Prediction </a>
                                        <a href="https://216.245.193.174/testimonials/reports/character-and-destiny/en/" style="background:none;font-weight:normal;padding-top:2px;">Character and destiny report </a>
                                        <a href="https://216.245.193.174/testimonials/reports/astrology-calendar/en/" style="background:none;font-weight:normal;padding-top:2px;">Astrology calendar</a>
                                        <a href="https://216.245.193.174/testimonials/reports/comprehensive-lover/en/" style="background:none;font-weight:normal;padding-top:2px;">Comprehensive Lover</a>
                                        <a href="https://216.245.193.174/testimonials/reports/psychological-analysis/en/" style="background:none;font-weight:normal;padding-top:2px;">Psychological Analysis</a>
                                        <a href="https://216.245.193.174/testimonials/reports/childs-horoscope/en/" style="background:none;font-weight:normal;padding-top:2px;">Child's Horoscope </a>
                                        <a href="https://216.245.193.174/testimonials/reports/career-and-vocation/en/" style="background:none;font-weight:normal;padding-top:2px;">Career and Vocation</a>
                                    </dd>
                                </li>
                            </ul>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</article>