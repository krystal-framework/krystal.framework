Profiler component
==================

This component is just a tool which can count script's taken time and used amount of memory. 
The service is automatically available in controllers and all templates.

In controllers, you can access it so : $this->profiler->...
In view templates, there's its instance and accessible like so: $profiler->...

If you want to use it as a standalone library, then just set-up PSR-0 auto-loading and then simply instantiate a profiler, like this:

$profiler = new \Krystal\Profiler\Profiler();
