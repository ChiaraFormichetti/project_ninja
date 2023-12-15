<?php

namespace Model\Storage;


use Model\QueryBuilder;
use Model\Table\Reservation;


class ReservationStorage extends BaseStorage
{
   protected $connection;
   protected $queryBuilder;
   //protected $tableColumns;
   public function __construct()
   {
      parent::__construct();
      $this->queryBuilder = new QueryBuilder('prenotazioni');
   }
   //ci potremmo fare i controlli, abbiamo l'array delle colonne, controlliamo che le colonne inserite siano all'interno dell'array
   //il controllo lo farei più nel manager che nello storage!!!!
   //quindi l'oggetto new reservation lo faremo li, posto in cui faremo il manager di tutti gli errori !

   //no *, 
   /*
   public function getPage($parameters = [])
   {
      try {
         $this->queryBuilder->select()
            ->countElements('*');
         if (isset($parameters['name'])) {
            $this->queryBuilder->where('nome', 'LIKE', '%' . $parameters['name'] . '%');;
         }
         if (isset($parameters['enter'])) {
            $this->queryBuilder->where('ingresso', '=', $parameters['enter'], 'OR');
         }
         if (isset($parameters['time'])) {
            $this->queryBuilder->where('ingresso', $parameters['time'], date('Y-m-d'));
         }
         if (isset($parameters['cancellazione'])) {
            $this->queryBuilder->where('cancellazione', '=', $parameters['cancellazione']);
         }
         if (isset($parameters['id'])) {
            $this->queryBuilder->where('id', '=', $parameters['id']);
         }
         $query = $this->queryBuilder->getQuery();
         $count = $this->connection->query($query)->fetchColumn();
         $resultForPage = (isset($parameters['number']) && is_numeric($parameters['number'])) ? $parameters['number'] : 10;
         $totPages = ceil($count / $resultForPage);
         $currentPage = (isset($parameters['page']) && is_numeric($parameters['page'])) ? $parameters['page'] : 1;
         $start = ($currentPage - 1) * $resultForPage;
         $this->queryBuilder
            ->selectColumns(['*']);
         if (isset($parameters['name'])) {
            $this->queryBuilder->where('nome', 'LIKE', '%' . $parameters['name'] . '%');
         }
         if (isset($parameters['enter'])) {
            $this->queryBuilder->where('ingresso', '=', $parameters['enter'], 'OR');
         }
         if (isset($parameters['time'])) {
            $this->queryBuilder->where('ingresso', $parameters['time'], date('Y-m-d'));
         }
         if (isset($parameters['cancellazione'])) {
            $this->queryBuilder->where('cancellazione', '=', $parameters['cancellazione']);
         }
         if (isset($parameters['id'])) {
            $this->queryBuilder->where('id', '=', $parameters['id']);
         }
         $this->queryBuilder->orderBy('ingresso')
            ->limit($start, $resultForPage);
         $query = $this->queryBuilder->getQuery();
         $reservations = [];
         foreach ($this->connection->query($query) as $row) {
            $reservations[] = $row;
         }
         return [
            'reservations' => $reservations,
            'totalPages' => $totPages,
            'currentPage' => $currentPage
         ];
      } catch (\Exception $e) {
         error_log('Errore durante il recupero delle prenotazioni: ' . $e->getMessage());
         return $e->getMessage();
      }
   }
   //metodo page ( queryBuilder ) {
      //select sql_calc_found_rows
*/
   //}

   //prova con le window 
   /*SELECT id, count(*) over() as totale 
FROM `prenotazioni` 
WHERE cancellazione = 0
limit 10;

select * from prenotazioni p where p.id in(1, 2)
*/

//altrimenti su get pages cerchi gli id e conteggi quante prenotazioni sono sul foreach e mandi all'altra l'id ddelle prenotazioni cercate e il conteggio
   public function getPages( $queryBuilder, $page=null, $parameters=[]){
     // $serializedQueryBuilder = serialize($queryBuilder);
      //$cloneQueryBuilder = unserialize($serializedQueryBuilder);
      $cloneQueryBuilder = $this->queryBuilder;
      $cloneQueryBuilder->countElements('id');
      $query = $cloneQueryBuilder->getQuery();
      $count = $this->connection->query($query)->fetchColumn();
      $resultForPage = (isset($parameters['number']) && is_numeric($parameters['number'])) ? $parameters['number'] : 10;
      $totPages = ceil($count / $resultForPage);
      $currentPage = (isset($page) && is_numeric($page)) ? $page : 1;
      $start = ($currentPage - 1) * $resultForPage;
      return [
         'count' => $count,
         'start' => $start,
         'resultForPage' => $resultForPage,
         'currentPage' => $currentPage,
         'totalPages' => $totPages
      ];
      
   }

   public function getReservation($page=null, $parameters=[])
   { 
      try {

        $cloneQueryBuilder = $this->queryBuilder;
         //$cloneQueryBuilder->select()
         $cloneQueryBuilder->select()
            ->where('ingresso', '>=', date('Y-m-d'))
            ->where('cancellazione', '=', 0, 'AND')
            ->orderBy('ingresso');
         $pageResult = $this->getPages($cloneQueryBuilder, $page, $parameters);
         $cloneQueryBuilder->selectColumns(['*'])
                           ->limit($pageResult['start'],$pageResult['resultForPage']);
         $query = $cloneQueryBuilder->getQuery();
         $reservations = [];
         foreach ($this->connection->query($query) as $row) {
            $reservations[] = $row;
         }
         return [
            'reservations' => $reservations,
            'totalPages' => $pageResult['totalPages'],
            'currentPage' => $pageResult['currentPage'],
            'count' => $pageResult['count']
         ];
         //se nella query builder viene lanciata un'eccezione, la catturiamo, registriamo il messaggio di errore nel log degli errori
         //e restituiamo un array vuoto !
      } catch (\Exception $e) {
         error_log('Errore durante il recupero delle prenotazioni: ' . $e->getMessage());
         return $e->getMessage();
      }
   }

   public function getReservationById($id)
   {
      try {
         $this->queryBuilder->select()
            ->selectColumns(['*'])
            ->where('id', '=', $id);
         $query = $this->queryBuilder->getQuery();
         $reservations = [];
         foreach ($this->connection->query($query) as $row) {
            $reservations[] = $row;
         }
         return $reservations;
      } catch (\Exception $e) {
         error_log('Errore durante il recupero della prenotazione: ' . $e->getMessage());
         return $e->getMessage();
      }
   }

   //Stampa le prenotazioni passate
   public function getHistoricReservation($parameters = [])
   {
      try {
         $this->queryBuilder->select()
            ->selectColumns(['*'])
            ->where('ingresso', '<', date('Y-m-d'))
            ->where('cancellazione', '=', 0, 'AND')
            ->orderBy('ingresso');

         $query = $this->queryBuilder->getQuery();
         $reservations = [];
         foreach ($this->connection->query($query) as $row) {
            $reservations[] = $row;
         }
         return $reservations;
      } catch (\Exception $e) {
         error_log('Errore durante il recupero delle prenotazioni nello storico: ' . $e->getMessage());
         return $e->getMessage();
      }
   }
   //Stampa le prenotazioni nel cestino
   public function getTrashReservation()
   {
      try {
         $this->queryBuilder->select()
            ->selectColumns(['*'])
            ->where('cancellazione', '=', 1)
            ->orderBy('ingresso');

         $query = $this->queryBuilder->getQuery();
         $reservations = [];
         foreach ($this->connection->query($query) as $row) {
            $reservations[] = $row;
         }
         return $reservations;
      } catch (\Exception $e) {
         error_log('Errore durante il recupero delle prenotazioni nel cestino: ' . $e->getMessage());
         return $e->getMessage();
      }
   }
   //Stampa le prenotazioni cercate
   public function searchReservation($name, $enter)
   {
      try {
         $this->queryBuilder->select()
            ->selectColumns(['*']);
         if ($name) {
            //La clausola like consente di eseguire una ricerca di stringhe simili o paziali (senza il '%' ci dà la corrispondenza esatta)
            //Il wildcard % indica che la stinga $name può essere tovata in qualsiasi parte del campo 'nome' (pima e/o dopo)
            $this->queryBuilder->where('nome', 'LIKE', '%' . $name . '%');
         }
         if ($enter) {
            $this->queryBuilder->where('ingresso', '=', $enter, 'OR');
         }
         $this->queryBuilder
            ->where('ingresso', '>=', date('Y-m-d'), 'AND')
            ->where('cancellazione', '=', 0, 'AND')
            ->orderBy('ingresso');
         $query = $this->queryBuilder->getQuery();
         $reservations = [];
         foreach ($this->connection->query($query) as $row) {
            $reservations[] = $row;
         }
         return $reservations;
      } catch (\Exception $e) {
         error_log('Errore durante il recupero delle prenotazioni da cercare: ' . $e->getMessage());
         return $e->getMessage();
      }
   }

   //Aggiunge una prenotazione
   public function addReservation(array $body)
   {
      try {
         $this->queryBuilder->insert()
            ->insert_into($body);
         $query = $this->queryBuilder->getQuery();
         $ris = $this->connection->query($query);
         return $ris->rowCount();
      } catch (\Exception $e) {
         error_log("Errore durante l'aggiunta della prenotazione : " . $e->getMessage());
         return $e->getMessage();
      }
   }

   //Sposta la prenotazione nel cestino
   public function trashReservation(int $id)
   {
      try {
         $this->queryBuilder->update()
            ->updateFunction('cancellazione', 1)
            ->where('id', '=', $id);
         $query = $this->queryBuilder->getQuery();
         $trash = $this->connection->query($query);
         return $trash;
      } catch (\Exception $e) {
         error_log("Errore durante l'aggiunta della prenotazione : " . $e->getMessage());
         return $e->getMessage();
      }
   }
   //Modifica prenotazione
   public function editReservation(array $updateData, int $id)
   {  //per ogni colonna set value
      try {
         $this->queryBuilder->update();
         foreach ($updateData as $column => $value) {
            $this->queryBuilder->updateFunction($column, $value);
         }
         $this->queryBuilder->where('id', '=', $id);
         $query = $this->queryBuilder->getQuery();
         $edit = $this->connection->query($query);
         return $edit;
      } catch (\Exception $e) {
         error_log("Errore durante l'aggiunta della prenotazione : " . $e->getMessage());
         return $e->getMessage();
      }
   }
   //Cancella definitivamente una prenotazione
   public function deleteReservation($id)
   {
      try {
         $this->queryBuilder->delete()
            ->where('id', '=', $id);
         $query = $this->queryBuilder->getQuery();
         $delete = $this->connection->query($query);
         return $delete;
      } catch (\Exception $e) {
         error_log("Errore durante l'aggiunta della prenotazione : " . $e->getMessage());
         return $e->getMessage();
      }
   }
}
