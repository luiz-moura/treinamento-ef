<?php
require_once "helper.php";

Class Groups {
  protected array $groups;

  public function __construct(array $groups){
    $this->groups = $groups;
  }

  public function getGroups() {
    return $this->groups;
  }

  public function setGroups(array $groups) {
    $this->groups = $groups;
  }

  public function getGroupById(int $id) {
    $key = array_search($id, array_column($this->groups, "id"));

    return $this->groups[$key];
  }

  public function groupsToString() {
    print("+--------------------------------------------+\n");
    print("|              LISTA DE GRUPOS               |\n");
    print("+----+---------------------------------------+\n");
    print("| ID |                 NOME                  |\n");
    print("+----+---------------------------------------+\n");
    $i = 1;
    foreach($this->getGroups() as $group) {
      print("| " . mb_str_pad($i, 3) . "| ");
      print(mb_str_pad($group["name"], 38) . "|\n");
      $i++;
    }
    print("+----+---------------------------------------+\n");
  }

  public function addGroup(string $group) {
    $ids = array_column($this->getGroups(), "id");
    $id = max($ids) + 1;
      
    $group = ["id" => $id, "name" => $group];

    $this->setGroups([...$this->getGroups(), $group]);
  }
}