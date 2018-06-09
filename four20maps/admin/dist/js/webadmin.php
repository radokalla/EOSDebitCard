<?php
/*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your language:
 * 'en' - English
 * 'de' - German
 * 'fr' - French
 * 'it' - Italian
 * 'nl' - Dutch
 * 'se' - Swedish
 * 'sp' - Spanish
 * 'dk' - Danish
 * 'tr' - Turkish
 * 'cs' - Czech
 * 'ru' - Russian
 * 'auto' - autoselect
 *//*
 * webadmin.php - a simple Web-based file manager
 * Copyright (C) 2004  Daniel Wacker <daniel.wacker@web.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * -------------------------------------------------------------------------
 * While using this script, do NOT navigate with your browser's back and
 * forward buttons! Always open files in a new browser tab!
 * -------------------------------------------------------------------------
 *
 * This is Version 0.9, revision 11
 * =========================================================================
 *
 * Changes of revision 11
 * <daniel.wacker@web.de>
 *    fixed handling if folder isn't readable
 *
 * Changes of revision 10
 * <alex-smirnov@web.de>
 *    added Russian translation
 * <daniel.wacker@web.de>
 *    added </td> to achieve valid XHTML (thanks to Marc Magos)
 *    improved delete function
 * <ava@asl.se>
 *    new list order: folders first
 *
 * Changes of revision 9
 * <daniel.wacker@web.de>
 *    added workaround for directory listing, if lstat() is disabled
 *    fixed permisson of uploaded files (thanks to Stephan Duffner)
 *
 * Changes of revision 8
 * <okankan@stud.sdu.edu.tr>
 *    added Turkish translation
 * <j@kub.cz>
 *    added Czech translation
 * <daniel.wacker@web.de>
 *    improved charset handling
 *
 * Changes of revision 7
 * <szuniga@vtr.net>
 *    added Spanish translation
 * <lars@soelgaard.net>
 *    added Danish translation
 * <daniel.wacker@web.de>
 *    improved rename dialog
 *
 * Changes of revision 6
 * <nederkoorn@tiscali.nl>
 *    added Dutch translation
 *
 * Changes of revision 5
 * <daniel.wacker@web.de>
 *    added language auto select
 *    fixed symlinks in directory listing
 *    removed word-wrap in edit textarea
 *
 * Changes of revision 4
 * <daloan@guideo.fr>
 *    added French translation
 * <anders@wiik.cc>
 *    added Swedish translation
 *
 * Changes of revision 3
 * <nzunta@gabriele-erba.it>
 *    improved Italian translation
 *
 * Changes of revision 2
 * <daniel.wacker@web.de>
 *    got images work in some old browsers
 *    fixed creation of directories
 *    fixed files deletion
 *    improved path handling
 *    added missing word 'not_created'
 * <till@tuxen.de>
 *    improved human readability of file sizes
 * <nzunta@gabriele-erba.it>
 *    added Italian translation
 *
 * Changes of revision 1
 * <daniel.wacker@web.de>
 *    webadmin.php completely rewritten:
 *    - clean XHTML/CSS output
 *    - several files selectable
 *    - support for windows servers
 *    - no more treeview, because
 *      - webadmin.php is a >simple< file manager
 *      - performance problems (too much additional code)
 *      - I don't like: frames, java-script, to reload after every treeview-click
 *    - execution of shell scripts
 *    - introduced revision numbers
 *
/* ------------------------------------------------------------------------- */

/* Your langu