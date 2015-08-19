<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/19
 * Time: 10:58
 */

namespace Xjtuwangke\Printings\Epub;


use PHPePub\Core\EPub;
use PHPePub\Core\Structure\OPF\DublinCore;
use Xjtuwangke\Printings\Chapter;
use Xjtuwangke\Printings\Printing;
use Illuminate\Contracts\Config\Repository as Config;
use Xjtuwangke\Utils\Random;

class EpubPrinting extends Printing
{
    /**
     * @var EPub
     */
    protected $epub;

    /**
     * @inheritdoc
     */
    public function initialization( Config $config ){
        $this->epub = new EPub();
        $this->epub->setTitle( $config->get('title','untitled') );
        if( $isbn = $config->get('ISBN') ){
            $this->epub->setIdentifier( $isbn , EPub::IDENTIFIER_ISBN );
        }
        else{
            $this->epub->setIdentifier( $config->get('uri') , EPub::IDENTIFIER_URI );
        }
        $this->epub->setLanguage( $config->get('language' , 'zh-cn') ); // Not needed, but included for the example, Language is mandatory, but EPub defaults to "en". Use RFC3066 Language codes, such as "en", "da", "fr" etc.
        $this->epub->setDescription( $config->get('description') );
        $this->epub->setAuthor($config->get( 'author' ),$config->get( 'author' ));
        $this->epub->setPublisher($config->get('publisher.name'), $config->get('publisher.url')); // I hope this is a non existent address :)
        $this->epub->setDate(time()); // Strictly not needed as the book date defaults to time().
        $this->epub->setRights($config->get('copyright')); // As this is generated, this _could_ contain the name or licence information of the user who purchased the book, if needed. If this is used that way, the identifier must also be made unique for the book.
        $this->epub->setSourceURL($config->get('publisher.url'));
        $this->epub->addDublinCoreMetadata(DublinCore::CONTRIBUTOR, "PHP");
        $this->epub->buildTOC(NULL, "toc", "目录", TRUE, TRUE);
    }

    /**
     * @return string
     */
    public function getExtension(){
        return 'epub';
    }

    /**
     * @return string
     */
    public function getMimeType(){
        return 'application/epub+zip';
    }

    /**
     * @param $chapter
     * @return mixed
     */
    public function addChapter( Chapter $chapter ){
        $content = $this->getContentHeader( $chapter->getTitle() ) . $chapter->getContent() . $this->getBookEnd();
        $this->epub->addChapter( $chapter->getTitle() , $chapter->getFilename() ,  $content );
    }

    /**
     * @param string $title
     * @return string
     */
    public function getContentHeader( $title = 'Test Book' ){
        $content_start =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
            . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
            . "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
            . "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
            . "<head>"
            . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
            . "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\" />\n"
            . "<title>{$title}</title>\n"
            . "</head>\n"
            . "<body>\n";
        return $content_start;
    }

    /**
     * @return string
     */
    public function getBookEnd(){
        return "</body>\n</html>\n";
    }

    /**
     * @param null|string $filename
     * @return void
     */
    public function save( $filename = null ){
        if( is_null( $filename ) ){
            $filename = time() . Random::getRandStr(32);
        }
        $this->filename = $filename;
        $this->epub->finalize();
        $this->epub->saveBook( $filename , storage_path( $this->getPath() ) );
    }

    /**
     * @return string
     */
    public function streamContent(){
        $file = storage_path( $this->getFullPath() );
        $handle = fopen($file,"r");
        echo fread($handle,filesize($file));
        fclose($handle);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function streamResponse(){
        $this->epub->sendBook( $this->filename );
    }


}