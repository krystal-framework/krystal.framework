Profiler component
================

This component has only one service, which is called `$profiler` and as its name states should be used purely for profiling purposes. It can be accessed in controllers, just like another service. It has two methods:

## getTakenTime()

    \Krystal\Profiler\Profiler::getTakenTime()

Returns taken time by the script. The returned value is approximated. Returns float.

## getMemoryUsage()

    \Krystal\Profiler\Profiler::getMemoryUsage()

Returns used memory by the script.

# Usage example

Basically you'd use it like this in controllers:

    public function someAction()
    {
          $takenTime = $this->profiler->getTakenTime();
          $memoryUsage = $this->profiler->getMemoryUsage();
          
         // Now, you can pass these variables to the view service
    }