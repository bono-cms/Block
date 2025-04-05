Block module
=====

Your site may include global content blocks that you want to make easily editable—such as a contact phone number, email address, or physical location. This module is specifically designed to handle such cases. It works like this: you create a new block and then render it wherever needed in your theme template.

## Availbale methods

A predefined global service called `$block` is available in all templates. It provides the following methods:

    $block->render($id); // Returns the content of a block by its associated ID.
    $block->renderAsArray($id); // Returns the block content as an array, where each line becomes a separate array element.


## Example

Rendering a global email address that can be edited from the administration panel.

    <footer>
      <div class="container">
          <p>Our email: <?= $block->render(1); ?></p>
      </div>
    </footer>