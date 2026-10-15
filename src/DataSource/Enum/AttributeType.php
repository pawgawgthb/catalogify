<?php

declare(strict_types=1);

namespace App\DataSource\Enum;

enum AttributeType: string
{
    case TEXT = 'text';
    case LONG_TEXT = 'longText';
    case RICH_TEXT = 'richText';
    case NUMBER = 'number';
    case PRICE = 'price';
    case BOOLEAN = 'boolean';
    case DATE = 'date';
    case IMAGE = 'image';
    case IMAGE_GALLERY = 'imageGallery';
    case URL = 'url';
    case ATTRIBUTES = 'attributes';
    case VARIANTS = 'variants';
}
