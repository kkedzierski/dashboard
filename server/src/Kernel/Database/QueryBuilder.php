<?php

declare(strict_types=1);

namespace App\Kernel\Database;

class QueryBuilder
{
    public function __construct(
        private readonly PdoProviderInterface $pdoProvider,
    ) {
    }

    /**
     * @var string[]
     */
    private array $queryParts = [];

    /**
     * @var string[]
     */
    private array $whereParams = [];

    private string $from = '';

    /**
     * @param string[] $columns
     */
    public function select(array $columns = []): self
    {
        if (empty($columns)) {
            $columns = ['*'];
        }

        $this->addQueryPart(sprintf('SELECT %s', implode(', ', $columns)));

        return $this;
    }

    public function insert(string $table, array $columns, array $values): self
    {
        $this->addQueryPart(sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, implode(', ', $columns), implode(', ', $values)));

        return $this;
    }

    public function setParameter(string $param, string $value, string $type = 'string'): self
    {
        $this->whereParams[] = [
            'param' => sprintf(':%s', $param),
            'value' => $value,
            'type' => $type,
        ];

        return $this;
    }

    public function setParameters(array $parameters): self
    {
        foreach ($parameters as $param => $value) {
            $this->setParameter($param, $value, 'string');
        }

        return $this;
    }

    public function where(string $statement): self
    {
        $this->addQueryPart(sprintf('WHERE %s', $statement));

        return $this;
    }

    public function from(string $table): self
    {
        $this->from = $table;
        $this->addQueryPart(sprintf('FROM %s', $this->from));

        return $this;
    }

    public function getQuery(): string
    {
        return implode(' ', $this->queryParts);
    }

    public function addQueryPart(string $queryPart): void
    {
        $this->queryParts[] = $queryPart;
    }

    public function resetQuery(): void
    {
        $this->queryParts = [];
        $this->whereParams = [];
        $this->from = '';
    }

    public function createQueryBuilder(): QueryBuilder
    {
        $this->resetQuery();

        return new QueryBuilder($this->pdoProvider);
    }

    private function prepareStatement(): \PDOStatement
    {
        $query = $this->getQuery();
        $statement = $this->pdoProvider->getPdo()->prepare($query);

        foreach ($this->whereParams as $param) {
            $statement->bindParam($param['param'], $param['value'], $this->getPdoType($param['type']));
        }

        return $statement;
    }

    public function getResult(): array
    {
        $statement = $this->prepareStatement();
        $statement->execute();

        return $statement->fetchAll();
    }

    private function getPdoType(string $type): int
    {
        return match ($type) {
            'string' => \PDO::PARAM_STR,
            'int' => \PDO::PARAM_INT,
            default => \PDO::PARAM_STR,
        };
    }

    public function execute(): void
    {
        $statement = $this->prepareStatement();

        $statement->execute();
    }

}
