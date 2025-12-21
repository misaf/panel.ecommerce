<?php

declare(strict_types=1);

namespace Misaf\Tenant\Tasks;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

final class SwitchFacadesTask implements SwitchTenantTask
{
    /**
     * @return void
     */
    public function forgetCurrent(): void
    {
        $this->clearFacadeInstancesInTheAppNamespace();
        $this->clearFacadeInstancesInTheAppModulesNamespace();
    }

    /**
     * @param IsTenant $tenant
     * @return void
     */
    public function makeCurrent(IsTenant $tenant): void
    {
        // No actions required for makeCurrent in this task
    }

    /**
     * @return void
     */
    private function clearFacadeInstancesInTheAppNamespace(): void
    {
        $this->clearFacadeInstancesByNamespace('App');
    }

    /**
     * @return void
     */
    private function clearFacadeInstancesInTheAppModulesNamespace(): void
    {
        $this->clearFacadeInstancesByNamespace('Misaf');
    }

    /**
     * @param string $namespace
     * @return void
     */
    private function clearFacadeInstancesByNamespace(string $namespace): void
    {
        // Collect all declared classes once and filter those that are facades within the specified namespace
        collect(get_declared_classes())
            ->filter(fn(string $className) => $this->isNamespaceFacade($className, $namespace))
            ->each(fn(string $className) => $this->clearResolvedFacadeInstance($className));
    }

    /**
     * @param string $className
     * @param string $namespace
     * @return bool
     */
    private function isNamespaceFacade(string $className, string $namespace): bool
    {
        return is_subclass_of($className, Facade::class)
               && (Str::startsWith($className, $namespace) || Str::startsWith($className, "Facades\\{$namespace}"));
    }

    /**
     * @param string $className
     * @return void
     */
    private function clearResolvedFacadeInstance(string $className): void
    {
        $className::clearResolvedInstance($className::getFacadeAccessor());
    }
}
