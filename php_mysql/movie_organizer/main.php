<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <title>Movie Organizer</title>
  </head>

  <body>

  <style>

  </style>

<h1>Movie Organizer</h1>

<hr />
<h2>Submit A New Movie</h2>
<form action="" method="post">

  <!-- Movie Title -->
  <label for="movieTitle">Movie Title</label>
  <input type="text" name="movieTitle" required /></br>

  <!-- Movie Year -->
  <label for="movieYear">Movie Year</label>
  <input type="number" name="movieYear" maxlength="4" required /></br>

  <!-- Movie Genre -->
  <label for="movieGenre">Movie Genre</label>
     <select name="movieGenre" required>
      <option value="">&nbsp;</option>
      <option value="Action">Action</option>
      <option value="Adventure">Adventure</option>
      <option value="Animated">Animated</option>
      <option value="Comedy">Comedy</option>
      <option value="Horror">Horror</option>
      <option value="Romance">Romance</option>
      <option value="Sci-Fi">Sci-Fi</option>
      <option value="Western">Western</option>
    </select><br>

  <!-- Movie LeadActor -->
  <label for="movieLeadActor">Movie LeadActor</label>
  <input type="text" name="movieLeadActor" required /></br>

  <!-- Movie Award -->
  <label for="movieAward">Movie Award</label>
  <input type="text" name="movieAward" required /></br>

  <!-- Movie Song -->
  <label for="movieSong">Movie Song</label>
  <input type="text" name="movieSong" /></br><br>

  <!-- Submit -->
  <input type="submit" name="submit_button" /><br>

</form>
<hr />

      <?php
class Movie {
  private $title;
  private $year;
  private $genre;
  private $leadActor;
  private $award;
  private $movieFileName = "movieSubmission.txt";
  private $songFileName = "songlist.txt";
  private $song;
  private $songList;

  // Movie constructs a Movie object
  public function Movie() {
    $this->title;
    $this->year;
    $this->genre;
    $this->leadActor;
    $this->award;
    $this->songList;
  }

  // setMovieSubmission stores and escapes the user's movie submission;
  public function setMovieSubmission() {
    $this->title = addslashes($_POST["movieTitle"]);
    $this->year = $_POST["movieYear"];
    $this->genre = addslashes($_POST["movieGenre"]);
    $this->leadActor = addslashes($_POST["movieLeadActor"]);
    $this->award = addslashes($_POST["movieAward"]);
    $this->song = addslashes($_POST["movieSong"]);
    $this->songList = $this->getSongList();
  }

  // archiveToFile writes a movie to a file.
  public function writeToFile($movieData, $file) {
    if (!file_exists(dirname($file))) {
      mkdir(dirname($file), 0777, true);
    }
    file_put_contents($file, (string) $movieData, LOCK_EX) or die("Unable to write to {$file}");
  }

  public function getFileName() {
    // recursively creates directory and file with widest possible permissions
    if (!file_exists(dirname("./MovieStore/Movies/"))) {
      mkdir(dirname("./MovieStore/Movies/"), 0777, true);
    }
    return "./MovieStore/Movies/{$this->title}_{$this->movieFileName}";
  }

  public function getSongFileName() {
    // recursively creates directory and file with widest possible permissions
    if (!file_exists(dirname("./MovieStore/Songs/"))) {
      mkdir(dirname("./MovieStore/Songs/"), 0777, true);
    }
    return "./MovieStore/Songs/{$this->title}_{$this->songFileName}";
  }

  public function getSongList() {
    if ((!file_exists($this->getSongFileName())) || (filesize($this->getSongFileName()) == 0)) {
      $this->writeToFile($this->song, $this->getSongFileName());
    }
    sleep(1);
    $SongArray = file($this->getSongFileName());

    return implode($SongArray);
  }

  // __toString is a Magic Method that returns the movie object as a human-readable text block.
  public function __toString() {
    $movieData = "Title:       $this->title\n";
    $movieData .= "Year:        $this->year\n";
    $movieData .= "Genre:       $this->genre\n";
    $movieData .= "Lead Actor:  $this->leadActor\n";
    $movieData .= "Award:       $this->award\n";
    $movieData .= "Song List:   {$this->song}\n";

    return $movieData;
  }

  // echoMovie echos the movie object as a human-readable HTML output.
  function echoMovie() {
    $movieData = <<<MovieData
<br>
Movie<br>
****************************<br>
<table>
  <tr>
    <td><b>Title:</b></td>
    <td>$this->title</td>
  </tr>
  <tr>
    <td><b>Year:</b></td>
    <td>$this->year</td>
  </tr>
  <tr>
    <td><b>Genre:</b></td>
    <td>$this->genre</td>
  </tr>
  <tr>
    <td><b>Lead Actor:</b></td>
    <td>$this->leadActor</td>
  </tr>
  <tr>
    <td><b>Award:</b></td>
    <td>$this->award</td>
  </tr>
  <tr>
    <td><b>Song List:</b></td>
    <td>{$this->getSonglist()}</td>
  </tr>
</table>
****************************<br>
MovieData;
    echo $movieData;
  }
}

class Song {
  private $title;
  private $fileName;

  public function Song() {
    $this->title;
    $this->fileName;
  }

  public function setSongSubmission() {
    $this->title = $_POST["movieSong"];
    $this->fileName = "./MovieStore/Songs/" . $_POST["movieTitle"];
  }

  public function appendSongToFile($songTitle, $file) {
    file_put_contents($file, $songTitle . "\n", FILE_APPEND | LOCK_EX) or die("Unable to write to {$file}");
  }

  public function getFileName() {
    return $this->fileName;
  }

  public function getTitle() {
    return $this->title;
  }

  // __toString is a Magic Method that returns the movie object as a human-readable text block.
  public function __toString() {
    $songData = "Title:       $this->title\n";
    $songData .= "Filename:        $this->fileName\n";
    return $songData;
  }
}

function main() {
  // if the user clicked the submit button
  if (isset($_POST['submit_button'])) {
    $movie = new Movie();
    $movie->setMovieSubmission();

    if ((file_exists($movie->getFileName())) && (filesize($movie->getFileName()) != 0)) {
      echo "<p>The movie you entered already exists!<br />\n";
      echo "Please submit a new movie.</p>";
      return;
    } else {
      $movie->writeToFile($movie->getSonglist(), $movie->getSongFileName());
      $movie->writeToFile($movie, $movie->getFileName());
      $movie->echoMovie();

      // destroy the specified movie object so it can't be resubmitted
      unset($movie);
    }
  }

  if (isset($_POST['song_submit'])) {
    $song = new Song();
    $song->setSongSubmission();

    $movefileName = "./MovieStore/Movies/" . substr($_POST['movieTitle'], 0, strpos($_POST['movieTitle'], '_')) . "_movieSubmission.txt";
    $song->appendSongToFile($song->getTitle(), $movefileName);
    $song->appendSongToFile($song->getTitle(), $song->getFileName());

    // destroy the specified movie object so it can't be resubmitted
    unset($movie);
  }

  // viewMovies
  if (isset($_POST['viewMovies_submit'])) {
    $moviePath = "MovieStore/Movies/" . substr($_POST['movieTitle'], 0, strpos($_POST['movieTitle'], '_')) . "_movieSubmission.txt";

    if ((file_exists($moviePath)) && (filesize($moviePath) != 0)) {
      $Movies = file($moviePath);
      echo "<br>";
      foreach ($Movies as $key => $value):
        echo "$value<br>";
      endforeach;
      echo "<br>";
    } else {
      echo "There was an error viewing the movie file.\n";
    }
  }

  // viewSongList
  if (isset($_POST['viewSongList_submit'])) {
    $songPath = "MovieStore/Songs/" . substr($_POST['movieTitle'], 0, strpos($_POST['movieTitle'], '_')) . "_songlist.txt";

    if ((file_exists($songPath)) && (filesize($songPath) != 0)) {
      $SongArray = file($songPath);
      foreach ($SongArray as $key => $value):
        echo "$value<br>";
      endforeach;
    } else {
      echo "There was an error viewing the song list.\n";
    }
  }

  // sortMoviesByTitle
  if (isset($_POST['sortMoviesByTitle_submit'])) {
    $moviePath = "./MovieStore/Movies/";

    if ((file_exists($moviePath))) {
      $MovieArray = array_diff(scandir($moviePath), array('.', '..'));
      unset($MovieArray[0]);
      unset($MovieArray[1]);
      $MovieArray = array_values($MovieArray);
      foreach ($MovieArray as $key => $value):
        echo substr($value, 0, strpos($value, '_')) . "<br />";
      endforeach;
    } else {
      echo "There was an error viewing the song list.\n";
    }

  }
}

// echoFileContents echos the file contents back to HTML.
function echoFileContents($movie) {
  $file = $movie->getFileName();
  if (file_exists($file) && is_readable($file)) {
    // nl2br inserts HTML line breaks before all newlines in a string
    echo nl2br(file_get_contents($file));
  }
}

// create options dropdown
function createOptionsDropdown($dirname) {
  // removes . and .. from the returned array from scandir:
  $files = array_diff(scandir($dirname), array('.', '..'));
  // Remove values from the first two indexes.
  unset($files[0]);
  unset($files[1]);
  // Reset the indexes without the original first two slots.
  $files = array_values($files);

  foreach ($files as $key => $value):
    echo "<option value='{$value}'>" . substr($value, 0, strpos($value, '_')) . "</option>";
  endforeach;
}

echo '<h2>Add a Song to a Movie\'s Song List</h2>';
echo '<form action="" method="POST" name="songAdder">';
echo '<label for="movieSong">Choose A Movie</label<br>';
echo '<select name="movieTitle" id="">';
createOptionsDropdown("./MovieStore/Songs");
echo '</select><br>';
echo '<label for="movieSong">Add a Song to a Movie\'s Song List</label>';
echo '<input type="text" name="movieSong" />';
echo '<input type="submit" name="song_submit" /><br>';
echo '</form>';
echo '<hr />';

echo '<h2>Learn More About A Movie</h2>';
echo '<form action="" method="POST" name="movieSongViewer">';
echo '<label for="movieSong">Choose A Movie</label><br>';
echo '<select name="movieTitle" id="">';
createOptionsDropdown("./MovieStore/Movies");
echo '</select>';
echo '<input type="submit" value="View Movie" name="viewMovies_submit" />';
echo '<input type="submit" value="View Song List" name="viewSongList_submit" />';
echo '<input type="submit" value="Sort Movie By Title" name="sortMoviesByTitle_submit" />';
echo '</form>';
echo '<hr />';

main();

?>

  </body>

</html>