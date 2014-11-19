<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SchemaOrgModel\AnnotationGenerator;

use Psr\Log\LoggerInterface;

/**
 * Abstract annotation generator.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
abstract class AbstractAnnotationGenerator implements AnnotationGeneratorInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var \EasyRdf_Graph[]
     */
    protected $graphs;
    /**
     * @var array
     */
    protected $cardinalities;
    /**
     * @var array
     */
    protected $config;
    /**
     * @var array
     */
    protected $classes;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        LoggerInterface $logger,
        array $graphs,
        array $cardinalities,
        array $config,
        array $classes
    ) {
        $this->logger = $logger;
        $this->graphs = $graphs;
        $this->cardinalities = $cardinalities;
        $this->config = $config;
        $this->classes = $classes;
    }

    /**
     * {@inheritdoc}
     */
    public function generateClassAnnotations($className)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function generateInterfaceAnnotations($className)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function generateConstantAnnotations($className, $constantName)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function generateFieldAnnotations($className, $fieldName)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function generateGetterAnnotations($className, $fieldName)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function generateSetterAnnotations($className, $fieldName)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function generateAdderAnnotations($className, $fieldName)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function generateRemoverAnnotations($className, $fieldName)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function generateUses($className)
    {
        return [];
    }

    /**
     * Converts a Schema.org range to a PHP type
     *
     * @param  array  $field
     * @param  bool   $adderOrRemover
     * @return string
     */
    protected function toPhpType(array $field, $adderOrRemover = false)
    {
        $range = $field['range'];

        $data = $range;
        switch ($range) {
            case 'Boolean':
                return 'boolean';
                break;
            case 'Date':
                // No break
            case 'DateTime':
                // No break
            case 'Time':
                return '\DateTime';
                break;
            case 'Number':
                // No break
            case 'Float':
                return 'float';
                break;
            case 'Integer':
                return 'integer';
                break;
            case 'Text':
                // No break
            case 'URL':
                return 'string';
                break;
        }

        if (isset($this->classes[$field['range']]['interfaceName'])) {
            $range = $this->classes[$field['range']]['interfaceName'];
        }

        if ($field['isArray'] && !$adderOrRemover) {
            if ($this->config['useDoctrineCollection']) {
                return sprintf('ArrayCollection<%s>', $range);
            }

            return sprintf('%s[]', $range);
        }

        return $range;
    }
}
