<?php

namespace Model\Storage;


use Model\QueryBuilder;

class ReservationStorage extends BaseStorage
{
   protected $connection;
   protected $queryBuilder;

   public function __construct()
   {
      parent::__construct('prenotazioni');
   }

   //prova con le window 
   /*SELECT id, count(*) over() as totale 
FROM `prenotazioni` 
WHERE cancellazione = 0
limit 10;*/

   //altrimenti su get pages cerchi gli id e conteggi quante prenotazioni sono sul foreach e mandi all'altra l'id ddelle prenotazioni cercate e il conteggio
   public function getReservationPages($page = null, $reservationsForPages = null, $parameters = [])
   {  
      $result = parent:: getPages($page,$reservationsForPages,$parameters);
      $this->queryBuilder->selectColumns(['*']);
      foreach ($result['id'] as $id) {
         $this->queryBuilder
            ->where('id', '=', $id, 'OR');
      }
      $this->queryBuilder->orderBy('ingresso')
         ->limit($result['start'], $result['resultForPage']);
      $query = $this->queryBuilder->getQuery();
      $reservations = [];
      foreach ($this->connection->query($query) as $row) {
         $reservations[] = $row;
      }
      return [
         'items' => $reservations,
         'totalPages' => $result['totalPages'],
         'count' => $result['count'],
      ];
   }

   public function getReservation($page = null, $resultForPage = null, $parameters = [])
   {
      try {
         $this->queryBuilder->select()
            ->where('ingresso', '>=', date('Y-m-d'))
            ->where('cancellazione', '=', 0, 'AND');
         if($resultForPage === 'all'){
            return parent::getAll();
         }
         return $this->getReservationPages($page, $resultForPage, $parameters);
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
   public function getHistoricReservation($page = null, $resultForPage = null, $parameters = [])
   {
      try {
         $this->queryBuilder->select()
         ->where('ingresso', '<', date('Y-m-d'))
         ->where('cancellazione', '=', 0, 'AND');
         if($resultForPage === 'all'){
            return parent::getAll();
         }
         return $this->getReservationPages($page, $resultForPage, $parameters);
      } catch (\Exception $e) {
         error_log('Errore durante il recupero delle prenotazioni nello storico: ' . $e->getMessage());
         return $e->getMessage();
      }
   }
   //Stampa le prenotazioni nel cestino
   public function getTrashReservation($page = null, $resultForPage = null, $parameters = [])
   {
      try {
         $this->queryBuilder->select()
            ->where('cancellazione', '=', 1);
            if($resultForPage === 'all'){
               return parent::getAll();
            }
         return $this->getReservationPages($page, $resultForPage, $parameters);
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

   public function searchHistoricReservation($name, $enter)
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
            ->where('ingresso', '<', date('Y-m-d'), 'AND')
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

   public function searchTrashReservation($name, $enter)
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
            ->where('cancellazione', '=', 1, 'AND')
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
         error_log("Errore durante la cancellazione della prenotazione : " . $e->getMessage());
         return $e->getMessage();
      }
   }

   public function restoreReservation(int $id)
   {
      try {
         $this->queryBuilder->update()
            ->updateFunction('cancellazione', 0)
            ->where('id', '=', $id);
         $query = $this->queryBuilder->getQuery();
         $restore = $this->connection->query($query);
         return $restore;
      } catch (\Exception $e) {
         error_log("Errore durante il ripristina della prenotazione : " . $e->getMessage());
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
         error_log("Errore durante la modifica della prenotazione : " . $e->getMessage());
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
         error_log("Errore durante la cancellazione della prenotazione : " . $e->getMessage());
         return $e->getMessage();
      }
   }
}
