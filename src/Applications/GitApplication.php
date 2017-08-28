<?php

namespace Laravel\Forge\Applications;

use Laravel\Forge\Contracts\ApplicationContract;

class GitApplication extends Application implements ApplicationContract
{
    /**
     * Application type.
     *
     * @return string
     */
    public function type()
    {
        return 'git';
    }

    /**
     * Indicates that application will be installed from GitHub repository.
     *
     * @param string $repository
     *
     * @return static
     */
    public function fromGithub(string $repository)
    {
        return $this->setRepositorySource('github', $repository);
    }

    /**
     * Indicates that application will be installed from Bitbucket repository.
     *
     * @param string $repository
     *
     * @return static
     */
    public function fromBitbucket(string $repository)
    {
        return $this->setRepositorySource('bitbucket', $repository);
    }

    /**
     * Indicates that application will be installed from Gitlab repository.
     *
     * @param string $repository
     *
     * @return static
     */
    public function fromGitlab(string $repository)
    {
        return $this->setRepositorySource('gitlab', $repository);
    }

    /**
     * Indicates that application will be installed from custom git repository.
     *
     * @param string $repository
     *
     * @return static
     */
    public function fromGit(string $url)
    {
        return $this->setRepositorySource('custom', $url);
    }

    /**
     * Indicates which branch from the repository should be used.
     *
     * @param string $branch
     *
     * @return static
     */
    public function usingBranch(string $branch)
    {
        return $this->setRepositoryBranch($branch);
    }

    /**
     * Set git provider and repository name.
     *
     * @param string $provider
     * @param string $repository
     *
     * @return static
     */
    protected function setRepositorySource(string $provider, string $repository)
    {
        $this->payload = [
            'provider' => $provider,
            'repository' => $repository,
        ];

        return $this;
    }

    /**
     * Set the branch name.
     *
     * @param string $branch
     *
     * @return static
     */
    protected function setRepositoryBranch(string $branch)
    {
        $this->payload['branch'] = $branch;

        return $this;
    }
}
