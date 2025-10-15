# Custom Liveblog Enhancements

This directory contains custom functionality for the Easy Liveblogs plugin.

## Table of Contents (TOC)

**File:** `elb-toc.php`

### Overview
Displays a Table of Contents above liveblog entries, showing the 10 latest entries with their timestamps and titles.

### Features
- **Automatic Display**: Appears on all liveblog posts (posts where `post_type=post` and `_elb_is_liveblog=1`)
- **Latest Entries**: Shows the 10 most recent liveblog entries
- **Formatted Timestamps**: Entry times are displayed using the site's configured date/time format
- **Anchor Links**: Each entry links to its corresponding position in the liveblog
- **Greek Title**: "Με μια Ματιά" ("At a Glance") as the TOC heading
- **Styled Presentation**: 
  - Time displayed in bold, black, 12px font
  - Title and links in color #1F5772
  - Clean, bordered container with light background

### Technical Details

#### Hook Used
The TOC is injected via the `elb_before_liveblog` action hook, which ensures it appears before the liveblog entries section.

#### Query Parameters
Fetches entries using:
```php
array(
    'post_type'      => 'elb_entry',
    'posts_per_page' => 10,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_key'       => '_elb_liveblog',
    'meta_value'     => $liveblog_id,
)
```

#### Anchor Mechanism
Links use the existing `elb_get_entry_url()` helper function, which creates URLs with an `entry` query parameter:
- Format: `https://site.com/liveblog-post/?entry=123`
- The JavaScript automatically scrolls to and highlights the entry with matching `data-elb-post-id`
- Works seamlessly with existing entry highlighting functionality

#### Styling
All styles are inline to ensure immediate effect without requiring external CSS files. The TOC adapts to the site's theme and existing liveblog styles.

### Filters Available

#### `elb_toc_title`
Modify the TOC heading text.
```php
add_filter( 'elb_toc_title', function( $title ) {
    return 'My Custom Title';
} );
```

#### `elb_toc_entries_args`
Customize the entry query arguments.
```php
add_filter( 'elb_toc_entries_args', function( $args, $liveblog_id ) {
    $args['posts_per_page'] = 20; // Show 20 instead of 10
    return $args;
}, 10, 2 );
```

### Behavior Notes

1. **Initial Load Only**: The TOC is rendered on initial page load and does not update dynamically as new entries are added via AJAX. This is by design to maintain a stable reference point for users.

2. **Empty State**: If there are no entries for the liveblog, the TOC is not displayed.

3. **Non-Liveblog Posts**: The TOC only appears on posts with liveblog functionality enabled. Regular posts are unaffected.

4. **Date/Time Format**: Respects WordPress site settings for date and time display formats via `elb_get_datetime_format()`.

5. **Internationalization**: All user-facing text uses WordPress i18n functions and the ELB_TEXT_DOMAIN for proper translation support.
