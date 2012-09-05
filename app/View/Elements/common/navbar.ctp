<nav>
    <ul>
        <li>
            <a href="/dashboard" class="<?php if($this->params['controller'] == 'dashboard') echo 'selected'; ?>" data-hover-element="span.icon-32">
                <span class="icon-32 icon-32-dashboard<?php if($this->params['controller'] == 'dashboard') echo '-blue'; ?>"></span>
                Dashboard
            </a>
        </li>
        <li>
            <a href="/gameservers" class="<?php if($this->params['controller'] == 'gameservers') echo 'selected'; ?>" data-hover-element="span.icon-32">
                <span class="icon-32 icon-32-servers<?php if($this->params['controller'] == 'gameservers') echo '-blue'; ?>"></span>
                Servers
            </a>
        </li>
        <li>
            <a href="/users" class="<?php if($this->params['controller'] == 'users') echo 'selected'; ?>" data-hover-element="span.icon-32">
                <span class="icon-32 icon-32-user<?php if($this->params['controller'] == 'users') echo '-blue'; ?>"></span>
                Users
            </a>
        </li>
        <li>
            <a href="/servers" class="<?php if($this->params['controller'] == 'servers') echo 'selected'; ?>" data-hover-element="span.icon-32">
                <span class="icon-32 icon-32-network<?php if($this->params['controller'] == 'server') echo '-blue'; ?>"></span>
                Infrastructure
            </a>
        </li>
    </ul>
</nav>