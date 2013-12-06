<?php
 
/**
 * SplClassLoader implementation that implements the technical interoperability
 * standards for PHP 5.3 namespaces and class names.
 *
 * http://groups.google.com/group/php-standards/web/final-proposal
 *
 *     // Example which loads classes for the Doctrine Common package in the
 *     // Doctrine\Common namespace.
 *     $classLoader = new SplClassLoader('Doctrine\Common', '/path/to/doctrine');
 *     $classLoader->register();
 *
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @author Roman S. Borschel <roman@code-factory.org>
 * @author Matthew Weier O'Phinney <matthew@zend.com>
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 * @author Fabien Potencier <fabien.potencier@symfony-project.org>
 */
class SplClassLoader
{
    private $_fileExtension = '.php';
    private $_namespace;
    private $_includePath;
    private $_namespaceSeparator = '\\';
 
    /**
	 * @var $excluse : Permet d'exclure certaines nom lors du remplacement auto du _ par /
	 */
	private $exclude = array();
	
	/**
	 * @var $union : Permet d'indiquer que toutes les classes dont le nom commence par 
	 * la clé du tableau sont dans le fichier correspondant à la valeur de la clé
	 */
	private $union = array();
 
    /**
     * Creates a new <tt>SplClassLoader</tt> that loads classes of the
     * specified namespace.
     * 
     * @param string $ns The namespace to use.
     */
    public function __construct($ns = null, $includePath = null)
    {
        $this->_namespace = $ns;
        $this->_includePath = $includePath;
    }
 
    /**
     * Sets the namespace separator used by classes in the namespace of this class loader.
     * 
     * @param string $sep The separator to use.
     */
    public function setNamespaceSeparator($sep)
    {
        $this->_namespaceSeparator = $sep;
    }
 
    /**
     * Gets the namespace seperator used by classes in the namespace of this class loader.
     *
     * @return void
     */
    public function getNamespaceSeparator()
    {
        return $this->_namespaceSeparator;
    }
 
    /**
     * Sets the base include path for all class files in the namespace of this class loader.
     * 
     * @param string $includePath
     */
    public function setIncludePath($includePath)
    {
        $this->_includePath = $includePath;
    }
 
    /**
     * Gets the base include path for all class files in the namespace of this class loader.
     *
     * @return string $includePath
     */
    public function getIncludePath()
    {
        return $this->_includePath;
    }
 
    /**
     * Sets the file extension of class files in the namespace of this class loader.
     * 
     * @param string $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->_fileExtension = $fileExtension;
    }
 
    /**
     * Gets the file extension of class files in the namespace of this class loader.
     *
     * @return string $fileExtension
     */
    public function getFileExtension()
    {
        return $this->_fileExtension;
    }
 
    /**
     * Installs this class loader on the SPL autoload stack.
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }
 
    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }
 
    /**
     * Loads the given class or interface.
     *
     * @param string $className The name of the class to load.
     * @return void
     */
    public function loadClass($className)
    {
    	if(null === $this->_namespace || $this->_namespace.$this->_namespaceSeparator === substr($className, 0, strlen($this->_namespace.$this->_namespaceSeparator)))
        {
            $fileName = '';
            $namespace = '';
			
            if(false !== ($lastNsPos = strripos($className, $this->_namespaceSeparator)))
            {
            	$namespace = str_replace($this->_namespace, '', substr($className, 0, $lastNsPos));
                $className = substr($className, $lastNsPos + 1);
				
			    $fileName = str_replace($this->_namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
			
			
			
			$no_replace = false;
			foreach($this->exclude as $exclude)
			{
				if(strpos($className, $exclude) !== false)
				{
					$no_replace = true;
				}
			}
			
			$other_file = false;
			if(strpos($className, '_') != false)
			{
				$debutNameClasse = substr($className, 0, strpos($className, '_')+1);
				if(key_exists($debutNameClasse, $this->union))
				{
					$other_file = $this->union[$debutNameClasse];
				}
			}
			
			if($no_replace == true)
			{
				$fileName .= $className.$this->_fileExtension;
			}
			elseif($other_file !== false)
			{
				$fileName .= $other_file.$this->_fileExtension;
			}
			else
			{
				$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . $this->_fileExtension;
			}
			
 			if(!is_null($this->_includePath))
 			{
 				$fileName = $this->_includePath . DIRECTORY_SEPARATOR . $fileName;
			}
			
            require_once($fileName);
        }
    }
	
	/**
	 * Exclus du remplacement automatique dans le nom.
	 * 
	 * @param string $nameClass : Le nom de la classe à exclure.
	 */
	public function exclude_classe_replace_dir($nameClass)
	{
		$this->exclude[] = $nameClass;
	}
	
	/**
	 * Permet d'indiquer que plusieurs classes sont réuni dans un seul fichier.
	 * 
	 * @param string $debut_name_classe : Le début du nom des classes.
	 * @param string $file 				: Le nom du fichier (sans l'extension) qui doit être inclus.
	 */
	public function reunion_classe($debut_name_classe, $file)
	{
		$this->union[$debut_name_classe] = $file;
	}
}