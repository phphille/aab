<?php


namespace Anax\MVC;

/**
 * Model for Users.
 *
 */
class CDatabaseModel implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;





     /**
     * Find and return all.
     *
     * @return array
     */
    public function findAll($tableName = null)
    {
        $source = $this->getSource();
        if($tableName != null){
            $source = $tableName;
        }
        $this->db->select()
                 ->from($source);
        $this->db->execute();
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }









    /**
     * Find and return specific.
     *
     * @return this
     */
    public function find($id)
    {
        $this->db->select()
                 ->from($this->getSource())
                 ->where("id = ?");
        $this->db->execute([$id]);
        return $this->db->fetchInto($this);
    }




    /**
     * Find and return specific.
     *
     * @return this
     */
    public function findUser($acronym, $password)
    {
        $this->db->select('id, acronym, medlemsTyp')
                 ->from('users')
                 ->where("acronym = '".$acronym."'")
                 ->andWhere('password = md5(concat("'.$password.'", salt))');
        $this->db->execute();

        return $this->db->fetchAll();
    }





     /**
     * Find and return specific.
     *
     * @return this
     */
    public function getSearch($textField, $table)
    {
        if($textField == null || empty($textField)){
            $this->db->select(' q.*,
                u.acronym as user,
                GROUP_CONCAT(DISTINCT t.name ORDER BY t.name) as tags
                from questions as q')
                ->join('questions2tags as Q2T', 'q.id = Q2T.idQuestions')
                ->join('users as u', 'q.user = u.id')
                ->join('tags as t', 'Q2T.idTags = t.id')
                ->where('q.titel LIKE "%'.$textField.'%" OR t.name LIKE "%'.$textField.'%"')
                ->groupBy('q.id')
                ->orderBy('q.created DESC')
                ->execute();

            return $this->db->fetchAll();
        }
        else{
            $this->db
            ->select('
            q.*,
            u.acronym as user,
            GROUP_CONCAT(DISTINCT t.name ORDER BY t.name) as tags
            from questions as q')
            ->join('questions2tags as Q2T', 'q.id = Q2T.idQuestions')
            ->join('users as u', 'q.user = u.id')
            ->join('tags as t', 'Q2T.idTags = t.id')
            ->groupBy('q.id')
            ->orderBy('q.created DESC')
            ->execute();

            return $this->db->fetchAll();
        }
    }








    /**
     * Find and return specific.
     *
     * @return this
     */
    public function updateUser($id, $array, $table, $password)
    {
        if($password){
            $this->db->update(
                        $table,
                        [
                            'email' => '?',
                            'lastName' => '?',
                            'firstName' => '?',
                            'password' => '?',
                        ],
                        "id = ?"
                    );
        }
        else{
            $this->db->update(
                        $table,
                        [
                            'email' => '?',
                            'lastName' => '?',
                            'firstName' => '?',
                        ],
                        "id = ?"
                    );
        }

        return $this->db->execute(array_merge($array, [$id]));
    }







    /**
     * Save current object/row.
     *
     * @param array $values key/values to save or empty to use object properties.
     *
     * @return boolean true or false if saving went okey.
     */
    public function save($values = [], $tableName = null)
    {

        if (isset($values['id'])) {
            $this->setProperties($values);
            $values = $this->getProperties();
        }

        if (isset($values['id'])) {
            return $this->update($values, $tableName);
        } else {
            return $this->create($values, $tableName);
        }
    }









    /**
     * Create new row.
     *
     * @param array $values key/values to save.
     *
     * @return boolean true or false if saving went okey.
     */
    public function create($values, $tableName = null)
    {
        $keys   = array_keys($values);
        $values = array_values($values);

        if($tableName == null){
            $this->db->insert(
                $this->getSource(),
                $keys
            );
        }
        else{
            $this->db->insert(
                $tableName,
                $keys
            );
        }

        $res = $this->db->execute($values);
        $this->id = $this->db->lastInsertId();

        return $res;
    }










    /**
     * Update row.
     *
     * @param array $values key/values to save.
     *
     * @return boolean true or false if saving went okey.
     */
    public function update($values, $tableName = null)
    {

        $keys   = array_keys($values);
        $values = array_values($values);

        // Its update, remove id and use as where-clause
        unset($keys['id']);
        $values[] = $this->id;


        if($tableName == null){
            $this->db->update(
                $this->getSource(),
                $keys,
                "id = ?"
            );
        }
        else{
            $this->db->update(
                $tableName,
                $keys,
                "id = ?"
            );
        }

        return $this->db->execute($values);
    }










    /**
     * Delete row.
     *
     * @param integer $id to delete.
     *
     * @return boolean true or false if deleting went okey.
     */
    public function delete($id)
    {
        $this->db->delete(
            $this->getSource(),
            'id = ?'
        );

        return $this->db->execute([$id]);
    }













    /**
     * Delete row.
     *
     * @param integer $id to delete.
     *
     * @return boolean true or false if deleting went okey.
     */
    public function softDelete($id)
    {
        $this->db->delete(
            $this->getSource(),
            'id = ?'
        );

        return $this->db->execute([$id]);
    }



















    /**
     * Set object properties.
     *
     * @param array $properties with properties to set.
     *
     * @return void
     */
    public function setProperties($properties)
    {
        // Update object with incoming values, if any
        if (!empty($properties)) {
            foreach ($properties as $key => $val) {
                $this->$key = $val;
            }
        }
    }










    /**
     * Get object properties.
     *
     * @return array with object properties.
     *
     * Jag skapar även metoden getProperties() som returnerar de properties som har med modellens databastabell att göra.
     * Jag använder metoden get_object_vars() för att hämta objektets properties. Sedan tar jag bort de properties som jag inte vill visa, i detta fallet $di och $db
     */
    public function getProperties()
    {
        $properties = get_object_vars($this);
        unset($properties['di']);
        unset($properties['db']);

        return $properties;
    }






    /**
     * Get the table name.
     *
     * @return string with the table name.
     *
     * Detta är en metod som hämtar klassens namn och strippar bort eventuellt namespace. Kvar bli User som är modell-klassens namn och namnet på tabellen i databasen, som döpts till user.
     */
    public function getSource()
    {
        return strtolower(implode('', array_slice(explode('\\', get_class($this)), -1)));
    }









    /**
     * Build a select-query.
     *
     * @param string $columns which columns to select.
     *
     * @return $this
     */
    public function query($columns = '*')
    {
        $this->db->select($columns)
                 ->from($this->getSource());

        return $this;
    }



    /**
     * Build the where part.
     *
     * @param string $condition for building the where part of the query.
     *
     * @return $this
     */
    public function where($condition)
    {
        $this->db->where($condition);

        return $this;
    }





    /**
     * Build the where part.
     *
     * @param string $condition for building the where part of the query.
     *
     * @return $this
     */
    public function andWhere($condition)
    {
        $this->db->andWhere($condition);

        return $this;
    }



    /**
     * Build the where part.
     *
     * @param string $condition for building the where part of the query.
     *
     * @return $this
     */
    public function orderBy($condition)
    {
        $this->db->orderBy($condition);

        return $this;
    }





    /**
     * Execute the query built.
     *
     * @param string $query custom query.
     *
     * @return $this
     */
    public function execute($params = [])
    {
        $this->db->execute($this->db->getSQL(), $params);
        $this->db->setFetchModeClass(__CLASS__);

        return $this->db->fetchAll();
    }

}
