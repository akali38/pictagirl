<?php
if(!class_exists('MonstroLikesAndViews')) {
    class MonstroLikesAndViews
    {
        private static $instance = false;
        private $data = array();

        public static function getInstance()
        {
            if (false === self::$instance) {
                self::$instance = new MonstroLikesAndViews();
            }
            return self::$instance;
        }

        public function __construct()
        {
            if (!have_posts()) {
                return;
            }
            global $wpdb;
            global $wp_query;
            $postIds = array();
            foreach ($wp_query->posts as $post) {
                $postIds[] = $post->ID;
                $this->data[$post->ID] = array(
                    'views' => 0,
                    'votes' => 0,
                    'vote' => 0
                );
            }
            $monstroViewsTable = $wpdb->prefix . 'monstro_views';
            $monstroVotesTable = $wpdb->prefix . 'monstro_votes';
            $postIds = implode(',', $postIds);
            $views = $wpdb->get_results(
                "
                    SELECT post_id, COUNT(user_ip) as views
                    FROM $monstroViewsTable
                    WHERE post_id IN (" . $postIds . ")
                    GROUP BY post_id
            ;"
            );
            foreach ($views as $view) {
                $this->data[$view->post_id]['views'] = $view->views;
            }

            $votes = $wpdb->get_results(
                "
                SELECT post_id, SUM(vote) as votes
                FROM $monstroVotesTable
                WHERE post_id IN (" . $postIds . ")
                GROUP BY post_id
            ;"
            );
            foreach ($votes as $vote) {
                $this->data[$vote->post_id]['votes'] = $vote->votes;
            }
            $userVotes = $wpdb->get_results(
                $wpdb->prepare("
                SELECT post_id, vote
                FROM $monstroVotesTable
                WHERE post_id in (" . $postIds . ")
                AND user_ip LIKE %s
            ;", is_user_logged_in() ? get_current_user_id() : $_SERVER['REMOTE_ADDR'])
            );
            foreach ($userVotes as $vote) {
                $this->data[$vote->post_id]['vote'] = $vote->vote;
            }
        }

        public function getViews()
        {
            return $this->data[get_the_ID()]['views'];
        }

        public function getVotes()
        {
            if (isset($this->data[get_the_ID()]['votes'])) {
                return $this->data[get_the_ID()]['votes'];
            } else {
                return FALSE;
            }
        }

        public function getVote()
        {
            if (isset($this->data[get_the_ID()]['vote'])) {
                return $this->data[get_the_ID()]['vote'];
            } else {
                return FALSE;
            }
        }

        public function hasUpvoted()
        {
            return $this->data[get_the_ID()]['vote'] > 0;
        }

        public function hasDownvoted()
        {
            return $this->data[get_the_ID()]['vote'] < 0;
        }
    }
}