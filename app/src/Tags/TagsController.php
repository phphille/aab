<?php


namespace Anax\Tags;

/**
 * A controller for users and admin related events.
 *
 */
class TagsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
    \Anax\MVC\TRedirectHelpers;


    /**
     * List all users.
     *
     * @return void
     */
    public function indexAction()
    {

        if($this->request->getGet('textField')!=null || !empty($this->request->getGet('textField'))){
             $this->db->select('
            t.*,
            (SELECT COUNT(*) TotalCount
                FROM questions2tags
                where idTags = t.id
                GROUP BY idTags) as count
            from tags as t')
            ->where('t.name LIKE "%'.strip_tags($this->request->getGet('textField')).'%"')
            ->groupBy('t.id')
            ->orderBy('3 DESC')
            ->execute();
        }
        else{
            $this->db->select('
            t.*,
            (SELECT COUNT(*) TotalCount
                FROM questions2tags
                where idTags = t.id
                GROUP BY idTags) as count
            from tags as t')
            ->groupBy('t.id')
            ->orderBy('3 DESC')
            ->execute();
        }

        $tags = $this->db->fetchAll();
        $textField = $this->request->getGet('textField');
        $this->views->add('tags/tags', [
            'tags' => $tags,
            'textField' => $textField,
            'title' => "Taggar",
        ], 'jumbotron');

    }




    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->tags = new \Anax\Tags\Tags();
        $this->tags->setDI($this->di);
    }





}
