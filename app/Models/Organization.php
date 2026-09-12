<?php

namespace App\Models;

use App\Models\Appraisals\AppraisalQuestionTemplate;
use App\Models\Bulletins\BulletinCategory;
use App\Models\Employees\Employee;
use App\Models\Helpdesks\Helpdesk;
use App\Models\Helpdesks\HelpdeskCategory;
use App\SystemModels\Auth\User;
use App\SystemModels\UserStamp;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;
    use UserStamp;

    protected $table   = 'iq_org';
    protected $guarded = ['id'];

    public function parent()
    {
        return $this->hasOne(Organization::class, 'id', 'parent_id')->withTrashed();
    }

    public function authorized1()
    {
        return $this->hasOne(Organization::class, 'id', 'authorized1')->withTrashed();
    }

    public function authorized2()
    {
        return $this->hasOne(Organization::class, 'id', 'authorized2')->withTrashed();
    }

    public function childs()
    {
        return $this->hasMany(Organization::class, 'parent_id', 'id')->withTrashed();
    }

    public function leader()
    {
        return $this->hasOne(Employee::class, 'org_id', 'id')->withTrashed();
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo(GlobalData::class, 'position_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function helpdeskCategories()
    {
        return $this->belongsToMany(HelpdeskCategory::class, 'iq_helpdesk_category_organization', 'organization_id', 'category_id');
    }

    public function hasHelpdeskCategory($categoryId)
    {
        return $this->helpdeskCategories->contains('id', $categoryId);
    }

    public function helpdeskOrganizations()
    {
        return $this->belongsToMany(Helpdesk::class, 'iq_helpdesk_organization', 'organization_id', 'helpdesk_id');
    }

    public function hasHelpdeskOrganization($helpdeskId)
    {
        return $this->helpdeskOrganizations->contains('id', $helpdeskId);
    }

    public function bulletinCategories()
    {
        return $this->belongsToMany(BulletinCategory::class, 'iq_bulletin_category_organization', 'organization_id', 'category_id');
    }

    public function hasBulletinCategory($categoryId)
    {
        return $this->bulletinCategories->contains('id', $categoryId);
    }

    public function templateOrganization()
    {
        return $this->belongsToMany(AppraisalQuestionTemplate::class, 'iq_appraisal_period_organization', 'organization_id', 'template_id');
    }

    public function hasTemplateOrganization($templateId)
    {
        return $this->templateOrganization->contains('id', $templateId);
    }

    public function filemangers()
    {
        return $this->belongsToMany(FileManager::class, 'iq_filemanager_organization', 'organization_id', 'filemanager_id');
    }

    public function hasFilemanager($filemanagerId)
    {
        return $this->filemangers->contains('id', $filemanagerId);
    }

    public static function resorting($parent)
    {
        $modules = static::where('parent_id', $parent)->orderBy('id')->get();
        $orderedModules = $modules->values();
        return $orderedModules;
    }

    public static function setPath($id)
    {
        $module = static::find($id);
        if (!$module) {
            return null;
        }

        $parent = $module->parent_id;
        $path = '/' . $id;

        $visited = [$id];
        while ($parent) {
            if (in_array($parent, $visited)) {
                throw new Exception("Loop detected in parent hierarchy for module ID: $id");
            }

            $path = '/' . $parent . $path;
            $visited[] = $parent;

            $parentModule = static::find($parent);
            $parent = $parentModule ? $parentModule->parent_id : null;
        }
        $module->update(['path' => $path]);
        return $path;
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'org_id', 'id');
    }
}
