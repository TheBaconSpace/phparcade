<?php if (!isset($_SESSION)) {
    session_start();
    $user = $_SESSION['user'];
}

PHPArcade\Users::userUpdatePlaycount();
global $params; ?>
<!--suppress Annotator -->
<?php
    $dbconfig = PHPArcade\Core::getDBConfig();
    PHPArcade\Core::doEvent('gamepage');
    $metadata = PHPArcade\Core::getPageMetaData();
    $game = PHPArcade\Games::getGame($params[1]);
    if (isset($game['id'])) {
        $time = PHPArcade\Core::getCurrentDate();
        $img = $dbconfig['imgurl'] . $game['nameid'] . EXT_IMG;
        $thumbnailurl = $img;
        $origgamename = $game['name'];
        $epoch = $game['time'];
        $dt = new DateTime("@$epoch");
        $game['time'] = date('M d, Y', $game['time']);
        PHPArcade\Games::updateGamePlaycount($game['id']); ?>
        <div class="row">
            <h1 class="page-header mt-3" itemprop="headline">
                <?php echo $game['name']; ?>
            </h1>
        </div>
        <div class="card-deck mt-4">
            <div class="card">
                <h3 class="card-header">
                    <?php echo gettext('description'); ?>
                </h3>
                <div class="card-body">
                    <p class="card-text text-primary">
                        <?php echo $game['desc']; ?>
                    </p>
                </div>
            </div>
            <div class="card">
                <h3 class="card-header">
                    <?php echo gettext('instructions'); ?>
                </h3>
                <div class="card-body">
                    <p class="card-text text-primary">
                        <?php echo $game['instructions']; ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
        PHPArcade\Scores::fixGameChamp($game['id']);
        $scores = PHPArcade\Scores::getScoreType('lowhighscore', $game['flags']) ? PHPArcade\Scores::getGameScore($game['id'], 'ASC', TOP_SCORE_COUNT) : PHPArcade\Scores::getGameScore($game['id'], 'DESC', TOP_SCORE_COUNT); ?>
        <!-- Game Code -->
        <div class="card-deck mt-4">
            <div class="card text-center">
                <h3 class="card-header">
                    <?php echo $game['name']; ?>
                </h3>
                <div class="card-body">
                    <?php echo PHPArcade\Ads::getInstance()->showAds(); ?>
                    <div class="clearfix invisible">&nbsp;</div><?php
                    $game['type'] = $game['type'] ?? '';
        switch ($game['customcode']) {
                        case null:
                            case '':
                                if ($game['type'] !== 'extlink') {
                                    echo $game['code'];
                                    PHPArcade\Core::doEvent('gameplay');
                                }
                                break;
                                default:
                                    echo $game['customcode'];
                    } ?>
                    <div class="clearfix invisible">&nbsp;</div><?php
                    echo PHPArcade\Ads::getInstance()->showAds(); ?>
                </div>
            </div>
        </div><?php
        if ($game['flags'] <> '') {  /* If there are flags set (i.e. NOT a Mochi game), then show the score table*/
            $i = 0; ?>
            <div class="card-deck mt-4">
                <div class="card">
                    <h2 class="card-header">
                        <?php echo gettext('top10score'); ?>
                    </h2>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo gettext('Ranking'); ?></th>
                                        <th><?php echo gettext('player'); ?></th>
                                        <th><?php echo gettext('score'); ?></th>
                                        <th><?php echo gettext('date'); ?></th>
                                    </tr>
                                </thead>
                                <tbody><?php
                                    if (count($scores) != 0) {
                                        foreach ($scores as $score) {
                                            ++$i;
                                            $d_score = date('m/d/Y', $score['date']);
                                            $champ = PHPArcade\Users::getUserbyID($score['player']); ?>
                                            <tr class="odd gradeA">
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <img alt="<?php echo $champ['username'];?>'s Gravatar"
                                                         class="img img-fluid rounded-circle"
                                                         data-src="<?php echo PHPArcade\Users::userGetGravatar($champ['username'], 40); ?>"
                                                         style="float:left"
                                                    />&nbsp;
                                                    <a href="<?php echo PHPArcade\Core::getLinkProfile($champ['id']); ?>">
                                                        <?php echo $champ['username']; ?>
                                                    </a>
                                                </td>
                                                <td><?php echo PHPArcade\Scores::formatScore($score['score']); ?></td>
                                                <td><?php echo $d_score; ?></td>
                                            </tr><?php
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Game Code -->
            <?php
        }
    } else {
        ?>
        <h1><?php echo gettext('404status'); ?></h1>
        <h2><?php echo gettext('404page'); ?></h2><?php
        PHPArcade\Core::returnStatusCode(404);
        die();
    } ?>
    <!-- Related Items -->
    <div class="card-deck mt-4">
        <div class="card">
            <h3 class="card-header">
                <?php echo gettext('additionalgames'); ?>
            </h3>
            <div class="card-deck mt-4"><?php
                $gameslikethis = PHPArcade\Games::getGamesLikeThis();
                foreach ($gameslikethis as $gamelikethis) {
                    $link = PHPArcade\Core::getLinkGame($gamelikethis['id']); ?>
                        <div class="card text-center border-0">
                            <a href="<?php echo $link; ?>"><?php
                                $img = $dbconfig['imgurl'] . $gamelikethis['nameid'] . EXT_IMG; ?>
                                <img alt="Play <?php echo $gamelikethis['name']; ?> online for free!"
                                     class="img img-fluid rounded"
                                     data-src="<?php echo $img; ?>"
                                     height="<?php echo $dbconfig['theight']; ?>"
                                     title="Play <?php echo $gamelikethis['name']; ?> online for free!"
                                     width="<?php echo $dbconfig['twidth']; ?>"
                                />
                            </a>
                            <div class="card-body">
                                <h3><?php echo $gamelikethis['name']; ?></h3>
                                <p class="card-text"><?php echo $gamelikethis['desc']; ?></p>
                                <p>
                                    <a href="<?php echo $link; ?>" class="btn btn-info">
                                        <?php echo gettext('playnow'); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    <?php
                }
                unset($gameslikethis); ?>
            </div>
        </div>
        <?php PHPArcade\Games::getGameModal();?>
    </div>
    <!-- Schema -->
    <?php include_once(INST_DIR . '/includes/js/Schema/gamepageschema.php');